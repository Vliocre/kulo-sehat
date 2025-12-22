<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TopicLandingController;
use App\Models\TopicGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TopicGuideController extends Controller
{
    public function index()
    {
        $guides = TopicGuide::orderBy('category_slug')
            ->orderBy('topic_slug')
            ->paginate(12);

        return view('admin.topic-guides.index', [
            'guides' => $guides,
            'categories' => $this->categories(),
        ]);
    }

    public function create()
    {
        return view('admin.topic-guides.create', [
            'guide' => new TopicGuide(),
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        TopicGuide::create($data);

        return redirect()
            ->route('admin.topic-guides.index')
            ->with('success', 'Panduan topik berhasil dibuat.');
    }

    public function edit(TopicGuide $topicGuide)
    {
        return view('admin.topic-guides.edit', [
            'guide' => $topicGuide,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, TopicGuide $topicGuide)
    {
        $data = $this->validatedData($request, $topicGuide->id);

        $topicGuide->update($data);

        return redirect()
            ->route('admin.topic-guides.index')
            ->with('success', 'Panduan topik berhasil diperbarui.');
    }

    public function destroy(TopicGuide $topicGuide)
    {
        $topicGuide->delete();

        return redirect()
            ->route('admin.topic-guides.index')
            ->with('success', 'Panduan topik berhasil dihapus.');
    }

    public function importDefaults(TopicLandingController $topicLandingController)
    {
        $library = $topicLandingController->topicLibrary();
        $count = 0;

        foreach ($library as $categorySlug => $topics) {
            foreach ($topics as $topicSlug => $data) {
                TopicGuide::updateOrCreate(
                    [
                        'category_slug' => $categorySlug,
                        'topic_slug' => Str::slug($topicSlug),
                    ],
                    [
                        'title' => $data['title'] ?? Str::headline(str_replace('-', ' ', $topicSlug)),
                        'summary' => $data['summary'] ?? null,
                        'symptoms' => $data['symptoms'] ?? [],
                        'care' => $data['care'] ?? [],
                        'prevention' => $data['prevention'] ?? [],
                        'danger_signs' => $data['danger_signs'] ?? [],
                        'palette' => $data['palette'] ?? null,
                    ]
                );
                $count++;
            }
        }

        return redirect()
            ->route('admin.topic-guides.index')
            ->with('success', "Impor selesai. {$count} panduan bawaan disalin ke database.");
    }

    protected function validatedData(Request $request, ?int $ignoreId = null): array
    {
        $categories = array_keys($this->categories());

        $validated = $request->validate([
            'category_slug' => ['required', Rule::in($categories)],
            'topic_slug' => [
                'required',
                'string',
                'max:120',
                Rule::unique('topic_guides')
                    ->ignore($ignoreId)
                    ->where(fn ($q) => $q->where('category_slug', $request->input('category_slug'))),
            ],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'palette' => ['nullable', 'string', 'max:120'],
            'symptoms' => ['nullable', 'string'],
            'care' => ['nullable', 'string'],
            'prevention' => ['nullable', 'string'],
            'danger_signs' => ['nullable', 'string'],
        ]);

        $validated['topic_slug'] = Str::slug($validated['topic_slug']);
        $validated['symptoms'] = $this->splitLines($request->input('symptoms'));
        $validated['care'] = $this->splitLines($request->input('care'));
        $validated['prevention'] = $this->splitLines($request->input('prevention'));
        $validated['danger_signs'] = $this->splitLines($request->input('danger_signs'));

        return $validated;
    }

    protected function splitLines(?string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $text))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    protected function categories(): array
    {
        return [
            'bayi' => 'Bayi',
            'remaja' => 'Remaja',
            'dewasa' => 'Dewasa',
            'lansia' => 'Lansia',
        ];
    }
}
