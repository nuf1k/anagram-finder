<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return response()->json($user);
        }

        return response()->json([
            'errors' => [
                'email' => 'The provided credentials do not match our records.',
                ]
        ], 422);
    }
}