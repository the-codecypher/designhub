<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function attemptLogin(Request $request) {
        // Attempt to issue a token to the user based on the login credentials
        $token = $this->guard()->attempt($this->credentials($request));

        // If token got issue
        if (! $token ) {
            return false;
        }

        // Get the authenticated user
        $user = $this->guard()->user();

        // If the user verified email
        if ( $user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail() ) {
            return false;
        }

        // Set the user's token
        $this->guard()->setToken($token);
        return true;
    }

    protected function sendLoginResponse(Request $request) {
        $this->clearLoginAttempts($request);

        // Get the token from authentication guard (JWT)
        $token = (string)$this->guard()->getToken();

        // Extract expiry date
        $expiration = $this->guard()->getPayload()->get('exp');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);
    }

    protected function sendFailedLoginResponse(Request $request) {
        $user = $this->guard()->user();

        if ( $user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail() ) {
            return response()->json(["errors" => [
                "verification" => "You need to verify your email account"
            ]]);
        }

        throw ValidationException::withMessages([
            $this->username() => "Authentication failed"
        ]);
    }
}
