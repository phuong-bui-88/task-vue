<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidLoginException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Token;
use App\Models\User;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws InvalidLoginException
     */
    public function login(UserRequest $request): JsonResponse
    {
        $name = $request->name;
        $verify = [];

        if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
            $user = User::whereEmail($name);
            $verify['email'] = $name;
        } else {
            $user = User::whereName($name);
            $verify['name'] = $name;
        }

        if (!$user) {
            throw new InvalidLoginException();
        }

        $verify['password'] = $request->password;

        if (!auth()->attempt($verify)) {
            throw new InvalidLoginException();
        }

        $user = auth()->user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Store a newly created resource in storage.
     * Signup.
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $request->merge(['password' => bcrypt($request->password)]);
        $user = User::create($request->all());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  UserRequest $request
     * @return \Illuminate\Http\Response|string
     */
    public function forgotPassword(UserRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw new InvalidLoginException();
        }

        $token = Password::getRepository()->create($user);

        Token::updateOrCreate(
            ['key' => $user->email],
            ['token' => $token]
        );

        $user->sendPasswordResetPassword($token);

        return 'ok';
    }

    public function resetPassword(UserRequest $request)
    {
        $tokenRecord = Token::where('token', $request->token)->first();

        if (!$tokenRecord) {
            throw new InvalidLoginException('Invalid token');
        }

        $data = $request->only('password', 'password_confirmation', 'token');
        $data['email'] = $tokenRecord->key;

        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET;
    }

    public function logout()
    {
        if (is_null(Auth::user())) {
            Auth::logout();

            return response()->json([
            'success'   => 'Logged out successfully',
            ]);
        }

        \Auth::user()->tokens()->delete();

        return response()->json([
            'success'   => 'Logged out successfully',
        ]);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::findOrCreateGoogleAuth($googleUser);
        $token = $user->createToken('auth-token')->plainTextToken;

        return redirect('/login?token=' . $token);
    }
}
