<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\TokenPasswordRequest;
use App\Http\Requests\TokenVerifyEmailRequest;
use App\Models\User;
use App\Service\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function viewRegister(): View
    {
        return view('auth.register');
    }

    public function viewDashboard(): View
    {
        return view('admin.dashboard');
    }

    public function viewLogin(): View
    {
        return view('auth.login');
    }

    public function viewTokenForm(): View
    {
        return view('auth.token_verify_form');
    }

    public function formForgotPassword(): View
    {
        return view('auth.forgot_password_form');
    }

    public function formTokenForgot(): View
    {
        return view('auth.token_verify_password');
    }

    public function formNewPassword(): View
    {
        return view('auth.new_password_forgot');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        
        if ($this->authService->register($request->only('user_name', 'email', 'password', 'phone_number', 'gender'))) {
            return redirect()->route('token')->with('success', __('auth.notify_register_success'));
        }

        return redirect()->route('register')->with('error', __('auth.notify_register_error'));
    }

    public function token(TokenVerifyEmailRequest $request): RedirectResponse
    {
        $token = $request->input('token_verify_email');

        if ($this->authService->token($token)) {
            return redirect()->route('login')->with('success', __('auth.notify_token_success'));
        }

        return redirect()->route('token.form')->with('error', __('auth.notify_token_error'));
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $loginResult = $this->authService->login($request->only('email', 'password'));

        if ($loginResult['notify']) {
            if (Auth::user()->role === User::ROLE_ADMIN) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('blogs.home');
        }

        return redirect()->route('login')->with('error', $loginResult['message']);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('blogs.home');
    }

    public function forgotPassword(ForgotPasswordRequest $request): RedirectResponse
    {
        $result = $this->authService->forgotPassword($request->only('email'));

        if ($result['notify']) {
            return redirect()->route('token.password')->with('success', $result['message']);
        }

        return redirect()->route('forgot.password')->with('error', $result['message']);
    }

    public function postTokenForgot(TokenPasswordRequest $request): RedirectResponse
    {
        $token = $request->input('token_reset_password');

        if ($this->authService->postTokenForgot($token)) {
            return redirect()->route('login')->with('success', __('auth.retrieve_password_success'));
        }

        return redirect()->route('forgot.password')->with('error', __('auth.retrieve_password_error'));
    }
}
