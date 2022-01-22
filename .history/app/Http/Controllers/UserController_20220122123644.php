<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);
        // try {
        //     $validation = $this->validate($request, [
        //         'login' => 'required|string',
        //         'password' => 'required|string'
        //     ]);


        //     return response()->json($validation);
        // } catch (\Throwable $th) {
        //     // return response()->json([
        //     //     "status" => "error",
        //     //     "errors" => $th->passes()
        //     // ]);
        // }
    }
}
