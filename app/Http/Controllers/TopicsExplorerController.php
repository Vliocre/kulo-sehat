<?php

namespace App\Http\Controllers;

use App\Models\TopicGuide;
use Illuminate\Support\Str;

class TopicsExplorerController extends Controller
{
    public function index()
    {
        $categories = [
            'bayi' => 'Bayi',
            'remaja' => 'Remaja',
            'dewasa' => 'Dewasa',
            'lansia' => 'Lansia',
        ];

        $library = (new TopicLandingController())->topicLibrary();
        $topicsByCategory = [];

        // Ambil data bawaan dari library
        foreach ($categories as $slug => $name) {
            $defaults = data_get($library, $slug, []);
            foreach ($defaults as $topicSlug => $data) {
                $topicsByCategory[$slug][$topicSlug] = [
                    'title' => $data['title'] ?? Str::headline(str_replace('-', ' ', $topicSlug)),
                    'summary' => $data['summary'] ?? '',
                ];
            }
        }

        // Override dengan data dari database jika ada
        $guides = TopicGuide::all();
        foreach ($guides as $guide) {
            if (!isset($categories[$guide->category_slug])) {
                continue;
            }
            $topicsByCategory[$guide->category_slug][$guide->topic_slug] = [
                'title' => $guide->title,
                'summary' => $guide->summary ?? '',
            ];
        }

        return view('articles.topics-all', [
            'categories' => $categories,
            'topicsByCategory' => $topicsByCategory,
        ]);
    }
}
