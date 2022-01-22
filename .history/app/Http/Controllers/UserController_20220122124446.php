<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "login" => "required|string",
            "status" => "required|string",
            "group" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => [
                    "code" => 422,
                    "message" => "Validation error",
                    "errors" => $validator->errors()
                ],
            ]);
        }

        return response()->json([
            "status" => "success"
        ]);
    }
}
