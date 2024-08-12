<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function store(Request $request): Response
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response('Please enter all fields in the correct format', 422);
        }

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response('Incorrect email or password', 401);
        }

        $request->session()->regenerate();

        return response(auth()->user());
    }
}
