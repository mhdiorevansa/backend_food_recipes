<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
   public function register(Request $request)
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
            'data' => [
               'id' => $user->id,
               'name' => $user->name,
               'email' => $user->email
            ],
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

   public function login(Request $request)
   {
      DB::beginTransaction();
      try {
         $request->validate([
            'email' => 'required|email',
            'password' => 'required'
         ]);
         $user = User::where('email', $request->email)->first();
         if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
               'email' => 'The provided credentials are incorrect',
            ]);
         }
         $token = $user->createToken('user login')->plainTextToken;
         DB::commit();
         return \response()->json([
            'data' => [
               'access_token' => $token,
               'token_type' => 'Bearer',
            ],
            'status' => 200,
            'message' => 'success'
         ]);
      } catch (\Throwable $th) {
         DB::rollBack();
         return \response()->json([
            'message' => $th->getMessage(),
            'status' => 500
         ]);
      }
   }

   public function logout(Request $request)
   {
      $request->user()->currentAccessToken()->delete();
   }
}
