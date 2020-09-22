<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function register(Request $request){

        $validator = Validator::make($request->json()->all() , [
            'name' => 'required|string|max:64',
            'email'     => 'required|string|email|unique:admins|max:128',
            'password'  => 'required|string|confirmed|min:8',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors(),400);
        }

        //Create Admin
        $admin = new Admins;
        $admin->name = $request->json()->get('name');
        $admin->email = $request->json()->get('email');
        $admin->password = Hash::make($request->json()->get('password'));
        $admin->save();

        $token = auth('admins')->login($admin);

        return response()->json(compact('admin', 'token'),201);
    }

    //
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('admins')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('admins')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('admins')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('admins')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admins')->factory()->getTTL() * 60
        ]);
    }
}
