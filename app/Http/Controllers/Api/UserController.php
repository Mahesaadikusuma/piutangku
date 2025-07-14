<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repository\Interface\UserInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

    protected UserInterface $userRepo;
    public function __construct(UserInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                "email" => 'required|email',
                "password" => 'required'
            ]);

            $credential = request(['email', 'password']);
            if (!Auth::attempt($credential)) {
                return response()->json([
                    "status" => "error",
                    "message" => "Unauthorized"
                ]);
            }

            $user = User::where("email", $request->email)->first();
            if (!Hash::check($request->password, $user->password)) {
                throw new Exception("Invalid Password");
            }

            // TODO: GENERATE TOKEN
            $tokenResult = $user->createToken("authToken")->plainTextToken;

            return response()->json([
                "status" => "success",
                "access_token" => $tokenResult,
                "token_type" => "Bearer",
                "user" => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'string', 'confirmed', Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Generate token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                "status" => "success",
                "access_token" => $tokenResult,
                "token_type" => "Bearer",
                "user" => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function logout(Request $request)
    {
        // TODO: REMOVE TOKEN
        $token = $request->user()->currentAccessToken()->delete();

        // RETURN TOKEN
        return response()->json([
            "message" => "Logout Success",
            "status" => "success",
            "token" => $token
        ]);
    }

    public function fetchUser(Request $request)
    {
        // GET DATA USER
        $user = $request->user();
        // bisa pakai cara ini $request->user()
        // bisa pakai cara ini Auth::user()


        // return reponse
        return response()->json([
            "status" => "success",
            "data" => $user
        ]);
    }

    public function users(Request $request)
    {
        try {
            $id = $request->input('id');
            $search = $request->input('search', '');
            $perPage = $request->input('perPage', 10);
            $sortBy = $request->input('sortBy', 'newest');

            $users = $this->userRepo->paginateFilteredUsers($search, $sortBy, $perPage);
            return response()->json([
                "status" => "success",
                "data" => $users
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
