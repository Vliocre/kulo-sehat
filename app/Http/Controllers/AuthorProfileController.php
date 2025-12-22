<?php

namespace App\Http\Controllers;

use App\Models\User;

class AuthorProfileController extends Controller
{
    public function show(User $user)
    {
        abort_if(!in_array($user->role, ['dokter', 'admin']), 404);

        return view('authors.show', [
            'author' => $user,
        ]);
    }
}
