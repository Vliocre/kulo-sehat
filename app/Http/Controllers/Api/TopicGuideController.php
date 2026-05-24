<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TopicLandingController;
use App\Models\TopicGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicGuideController extends Controller
{
    private const CATEGORIES = [
        'bayi' => 'Bayi',
        'remaja' => 'Remaja',
        'dewasa' => 'Dewasa',
        'lansia' => 'Lansia',
    ];

    public function categories()
    {
        return response()->json([
            'success' => true,
            'data' => collect(self::CATEGORIES)->map(fn ($name, $slug) => [
                'slug' => $slug,
                'name' => $name,
            ])->values(),
        ]);
    }

    public function index(Request $request)
    {
        $categorySlug = $request->query('category');

        if ($categorySlug) {
            return $this->byCategory($categorySlug);
        }

        return response()->json([
            'success' => true,
            'data' => collect(self::CATEGORIES)->mapWithKeys(fn ($name, $slug) => [
                $slug => $this->topicsForCategory($slug),
            ]),
        ]);
    }

    public function byCategory(string $categorySlug)
    {
        $categorySlug = Str::slug(strtolower($categorySlug));

        if (! isset(self::CATEGORIES[$categorySlug])) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori topik tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'category' => [
                    'slug' => $categorySlug,
                    'name' => self::CATEGORIES[$categorySlug],
                ],
                'topics' => $this->topicsForCategory($categorySlug),
            ],
        ]);
    }

    public function show(string $categorySlug, string $topicSlug)
    {
        $categorySlug = Str::slug(strtolower($categorySlug));
        $topicSlug = Str::slug(strtolower($topicSlug));

        if (! isset(self::CATEGORIES[$categorySlug])) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori topik tidak ditemukan',
            ], 404);
        }

        $topic = collect($this->topicsForCategory($categorySlug))
            ->firstWhere('slug', $topicSlug);

        if (! $topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topik penyakit tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $topic,
        ]);
    }

    private function topicsForCategory(string $categorySlug): array
    {
        $library = (new TopicLandingController())->topicLibrary();
        $topics = collect($library[$categorySlug] ?? [])
            ->map(fn ($topic, $slug) => $this->formatTopic($categorySlug, $slug, $topic));

        TopicGuide::where('category_slug', $categorySlug)
            ->orderBy('topic_slug')
            ->get()
            ->each(function (TopicGuide $guide) use (&$topics, $categorySlug) {
                $topics->put($guide->topic_slug, $this->formatTopic($categorySlug, $guide->topic_slug, [
                    'title' => $guide->title,
                    'summary' => $guide->summary,
                    'symptoms' => $guide->symptoms ?? [],
                    'care' => $guide->care ?? [],
                    'prevention' => $guide->prevention ?? [],
                    'danger_signs' => $guide->danger_signs ?? [],
                    'palette' => $guide->palette,
                ]));
            });

        return $topics->values()->all();
    }

    private function formatTopic(string $categorySlug, string $topicSlug, array $topic): array
    {
        return [
            'category_slug' => $categorySlug,
            'category_name' => self::CATEGORIES[$categorySlug] ?? Str::headline($categorySlug),
            'slug' => $topicSlug,
            'title' => $topic['title'] ?? Str::headline(str_replace('-', ' ', $topicSlug)),
            'summary' => $topic['summary'] ?? null,
            'symptoms' => $topic['symptoms'] ?? [],
            'care' => $topic['care'] ?? [],
            'prevention' => $topic['prevention'] ?? [],
            'danger_signs' => $topic['danger_signs'] ?? [],
            'palette' => $topic['palette'] ?? null,
        ];
    }
}
