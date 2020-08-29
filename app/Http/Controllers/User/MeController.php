<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Return current authenticated user
     *
     * @return JsonResponse
     */
    public function getMe() {
        if (auth()->check()) {
            $user = auth()->user();
            return response()->json(["user" => $user], 200);
        }

        return response()->json(null, 200);
    }
}

//            return response()->json(['user' => auth()->user()], 200);
