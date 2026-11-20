<?php

namespace App\Http\Controllers;

use App\Models\AiAssistantConversation;
use App\Models\AiAssistantMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AiAssistantController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless(in_array($user->role, ['pengguna', 'dokter'], true), 403);

        $conversation = AiAssistantConversation::with('messages')
            ->where('user_id', $user->id)
            ->first();

        return view('ai-assistant', compact('conversation'));
    }

    public function storeMessage(Request $request)
    {
        abort_unless(in_array($request->user()->role, ['pengguna', 'dokter'], true), 403);

        $data = $request->validate([
            'content' => ['nullable', 'string', 'max:2000'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif'],
        ]);
        $uploads = $request->file('images', []);
        $content = trim((string) ($data['content'] ?? ''));

        if ($content === '' && $uploads === []) {
            throw ValidationException::withMessages([
                'content' => 'Tulis pesan atau lampirkan setidaknya satu gambar.',
            ]);
        }

        $conversation = AiAssistantConversation::firstOrCreate(['user_id' => $request->user()->id]);
        $paths = [];

        try {
            foreach ($uploads as $upload) {
                $path = $upload->store('ai-assistant/'.$request->user()->id, 'local');
                if (! $path) {
                    throw new \RuntimeException('Gambar gagal disimpan.');
                }
                $paths[] = $path;
            }

            $message = $conversation->messages()->create([
                'role' => 'user',
                'content' => $content,
                'images' => $paths,
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($paths);
            throw $exception;
        }

        return response()->json([
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'images' => collect($message->images)->map(fn ($path, $index) => route('ai-assistant.image', [$message, $index]))->values(),
                'created_at' => $message->created_at->format('d M Y, H:i'),
            ],
            'ai_available' => false,
            'notice' => 'Pesan dan gambar tersimpan. Integrasi model AI belum dikonfigurasi, jadi analisis belum dapat diberikan.',
        ]);
    }

    public function image(Request $request, AiAssistantMessage $message, int $image)
    {
        abort_unless(
            $message->conversation()->where('user_id', $request->user()->id)->exists(),
            404
        );

        $path = $message->images[$image] ?? null;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path);
    }
}
