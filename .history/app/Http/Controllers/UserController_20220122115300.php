<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function createUser(Request $request) {
        $this->validate($request, [

        ]);
    }
}
