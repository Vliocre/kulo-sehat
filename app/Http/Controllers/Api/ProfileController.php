<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        try {
            $user = $request->user();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'phone' => $user->phone ?? null,
                    'age' => $user->age ?? null,
                    'height' => $user->height ?? null,
                    'weight' => $user->weight ?? null,
                    'about' => $user->about ?? null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil profil: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request)
    {
        try {
            $user = $request->user();
            
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string',
                'age' => 'sometimes|integer',
                'height' => 'sometimes|numeric',
                'weight' => 'sometimes|numeric',
                'about' => 'sometimes|string',
                'password' => 'sometimes|min:6|confirmed'
            ]);
            
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('phone')) {
                $user->phone = $request->phone;
            }
            if ($request->has('age')) {
                $user->age = $request->age;
            }
            if ($request->has('height')) {
                $user->height = $request->height;
            }
            if ($request->has('weight')) {
                $user->weight = $request->weight;
            }
            if ($request->has('about')) {
                $user->about = $request->about;
            }
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diupdate',
                'data' => $user
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update profil: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy(Request $request)
    {
        try {
            $user = $request->user();
            
            // Hapus semua token terlebih dahulu
            $user->tokens()->delete();
            
            // Hapus user
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus akun: ' . $e->getMessage()
            ], 500);
        }
    }
}