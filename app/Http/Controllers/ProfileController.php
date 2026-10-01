<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Article;
use App\Models\TopicGuide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    public function savedArticles(Request $request): View
    {
        $user = $request->user();
        $savedArticles = Article::query()
            ->select('articles.*')
            ->join('article_bookmarks', 'articles.id', '=', 'article_bookmarks.article_id')
            ->where('article_bookmarks.user_id', $user->id)
            ->where('articles.status', 'published')
            ->with('category')
            ->latest('article_bookmarks.created_at')
            ->get();

        return view('profile.saved-articles', compact('user', 'savedArticles'));
    }

    public function symptomCategories(Request $request): View
    {
        $user = $request->user();
        $topicCategories = $this->topicCategories();
        $topicsByCategory = $this->topicsByCategory();
        $savedTopicKeys = $this->savedTopicKeys($user->id);
        $savedTopics = collect($savedTopicKeys)
            ->map(function ($key) use ($topicsByCategory, $topicCategories) {
                [$categorySlug, $topicSlug] = explode('|', $key, 2);
                $topic = $topicsByCategory[$categorySlug][$topicSlug] ?? null;

                if (!$topic) {
                    return null;
                }

                return [
                    'category_slug' => $categorySlug,
                    'category_name' => $topicCategories[$categorySlug] ?? Str::headline($categorySlug),
                    'topic_slug' => $topicSlug,
                    'title' => $topic['title'],
                    'summary' => $topic['summary'] ?? '',
                ];
            })
            ->filter()
            ->values();

        return view('profile.symptom-categories', compact(
            'user',
            'savedTopics'
        ));
    }

    public function bookmarkTopic(Request $request, string $categorySlug, string $topicSlug): RedirectResponse
    {
        $topic = $this->resolveTopic($categorySlug, $topicSlug);
        abort_if(!$topic, 404);

        DB::table('topic_bookmarks')->updateOrInsert(
            [
                'user_id' => $request->user()->id,
                'category_slug' => strtolower($categorySlug),
                'topic_slug' => strtolower($topicSlug),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return back()->with('success', 'Gejala penyakit berhasil disimpan.');
    }

    public function unbookmarkTopic(Request $request, string $categorySlug, string $topicSlug): RedirectResponse
    {
        DB::table('topic_bookmarks')
            ->where('user_id', $request->user()->id)
            ->where('category_slug', strtolower($categorySlug))
            ->where('topic_slug', strtolower($topicSlug))
            ->delete();

        return back()->with('success', 'Gejala penyakit dihapus dari simpanan.');
    }

    private function topicCategories(): array
    {
        return [
            'bayi' => 'Bayi',
            'remaja' => 'Remaja',
            'dewasa' => 'Dewasa',
            'lansia' => 'Lansia',
        ];
    }

    private function topicsByCategory(): array
    {
        $topicCategories = [
            'bayi' => 'Bayi',
            'remaja' => 'Remaja',
            'dewasa' => 'Dewasa',
            'lansia' => 'Lansia',
        ];

        $library = (new TopicLandingController())->topicLibrary();
        $topicsByCategory = [];

        foreach ($topicCategories as $slug => $name) {
            foreach (data_get($library, $slug, []) as $topicSlug => $data) {
                $topicsByCategory[$slug][$topicSlug] = [
                    'title' => $data['title'] ?? Str::headline(str_replace('-', ' ', $topicSlug)),
                    'summary' => $data['summary'] ?? '',
                ];
            }
        }

        TopicGuide::all()->each(function ($guide) use (&$topicsByCategory, $topicCategories) {
            if (!isset($topicCategories[$guide->category_slug])) {
                return;
            }

            $topicsByCategory[$guide->category_slug][$guide->topic_slug] = [
                'title' => $guide->title,
                'summary' => $guide->summary ?? '',
            ];
        });

        return $topicsByCategory;
    }

    private function savedTopicKeys(int $userId): array
    {
        return DB::table('topic_bookmarks')
            ->where('user_id', $userId)
            ->latest()
            ->get(['category_slug', 'topic_slug'])
            ->map(fn ($bookmark) => $bookmark->category_slug . '|' . $bookmark->topic_slug)
            ->all();
    }

    private function resolveTopic(string $categorySlug, string $topicSlug): ?array
    {
        $categorySlug = strtolower($categorySlug);
        $topicSlug = strtolower($topicSlug);
        $topicsByCategory = $this->topicsByCategory();

        return $topicsByCategory[$categorySlug][$topicSlug] ?? null;
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
