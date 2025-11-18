<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(Request $request) {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);

            return ResponseHelper::make(200, 'success', 'Registration successful', $user);
        } catch (ValidationException $th) {
            return ResponseHelper::make(400, 'error', $th->getMessage(), $th->errors());
        } catch (\Throwable $th) {
            return ResponseHelper::make(400, 'error', 'Something went wrong');
        }
    }

    public function login(Request $request) {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if ($request->hasSession())
                    $request->session()->regenerate();

                return ResponseHelper::make(200, 'success', 'Login successful', $user);
            } else {
                return ResponseHelper::make(401, 'error', 'Invalid credentials');
            }
        } catch (ValidationException $th) {
            return ResponseHelper::make(400, 'error', $th->getMessage(), $th->errors());
        } catch (\Throwable $th) {
            return ResponseHelper::make(400, 'error', 'Something went wrong');
        }
    }

    public function user() {
        try {
            $user = Auth::user();
            return ResponseHelper::make(200, 'success', 'Success', $user);
        } catch (\Throwable $th) {
            return ResponseHelper::make(400, 'error', 'Something went wrong');
        }
    }

    public function logout() {
        try {
            Session::flush();
            Auth::logout();
            return ResponseHelper::make(200, 'success', 'Logout successful');
        } catch (\Throwable $th) {
            return ResponseHelper::make(400, 'error', 'Something went wrong');
        }
    }
}
