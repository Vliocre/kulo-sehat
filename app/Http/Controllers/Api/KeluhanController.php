<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeluhanController extends Controller
{
    /**
     * Get all keluhan for user
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

            $keluhan = Keluhan::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $keluhan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data keluhan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create new keluhan
     */
    public function store(Request $request)
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

            $validator = Validator::make($request->all(), [
                'judul' => 'required_without:title|string|max:255',
                'title' => 'required_without:judul|string|max:255',
                'isi' => 'required_without:description|string',
                'description' => 'required_without:isi|string',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $data = [
                'user_id' => $user->id,
                'judul' => $request->input('judul', $request->input('title')),
                'isi' => $request->input('isi', $request->input('description')),
                'status' => 'menunggu',
            ];
            
            $image = $request->file('gambar') ?: $request->file('image');

            if ($image) {
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('keluhan', $filename, 'public');
                $data['gambar'] = $path;
            }
            
            $keluhan = Keluhan::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Keluhan berhasil dikirim',
                'data' => $keluhan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan keluhan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get single keluhan
     */
    public function show(Request $request, $id)
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
    
    /**
     * Update keluhan
     */
    public function update(Request $request, $id)
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
                ->where('status', 'menunggu')
                ->findOrFail($id);
                
            $validator = Validator::make($request->all(), [
                'judul' => 'sometimes|string|max:255',
                'title' => 'sometimes|string|max:255',
                'isi' => 'sometimes|string',
                'description' => 'sometimes|string',
                'gambar' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
                'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $updateData = [];

            if ($request->filled('judul') || $request->filled('title')) {
                $updateData['judul'] = $request->input('judul', $request->input('title'));
            }

            if ($request->filled('isi') || $request->filled('description')) {
                $updateData['isi'] = $request->input('isi', $request->input('description'));
            }

            $image = $request->file('gambar') ?: $request->file('image');

            if ($image) {
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $updateData['gambar'] = $image->storeAs('keluhan', $filename, 'public');
            }

            $keluhan->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => 'Keluhan berhasil diupdate',
                'data' => $keluhan
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Keluhan tidak ditemukan atau sudah diproses'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete keluhan
     */
    public function destroy(Request $request, $id)
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
                ->where('status', 'menunggu')
                ->findOrFail($id);
                
            $keluhan->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Keluhan berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Keluhan tidak ditemukan atau sudah diproses'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
