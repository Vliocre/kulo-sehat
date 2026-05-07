<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Keluhan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HistoryController extends Controller
{
    /**
     * Get user's keluhan history
     */
    public function index(Request $request)
    {
        try {
            /** @var User|null $user */
            $user = $request->user();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $request->validate([
                'type' => 'nullable|in:all,keluhan,articles',
            ]);

            $type = $request->get('type', 'all'); // all, keluhan, articles
            
            $history = [];
            
            if ($type == 'all' || $type == 'keluhan') {
                $keluhan = Keluhan::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                $history['keluhan'] = $keluhan;
            }
            
            if ($type == 'all' || $type == 'articles') {
                $articles = Article::with('category')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(20)
                    ->get();
                $history['articles'] = $articles;
            }
            
            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil history: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get specific keluhan detail
     */
    public function showKeluhan(Request $request, $id)
    {
        try {
            /** @var User|null $user */
            $user = $request->user();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $keluhan = Keluhan::where('user_id', $user->id)
                ->findOrFail($id);
                
            return response()->json([
                'success' => true,
                'data' => $keluhan
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Keluhan tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
