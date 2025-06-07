<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            DB::commit();
            return \response()->json([
                'data' => $user,
                'message' => 'success',
                'status' => 200
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return \response()->json([
                'message' => $th->getMessage(),
                'status' => 500
            ]);
        }
    }
}
