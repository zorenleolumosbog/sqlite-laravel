<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserRequest;
use App\Http\Resources\V1\UserResource;
use App\Mail\ResetPassword;
use App\Models\User;
use App\Models\V1\PasswordResetToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::when($request->search, function ($query) use($request) {
                    $query->where('name', 'LIKE', '%'.$request->search.'%')
                        ->orWhere('email', 'LIKE', '%'.$request->search.'%');
                })
                ->when($request->date_from && $request->date_to, function ($query) use($request) {
                    $query->whereDate('created_at', '>=', $request->date_from)
                            ->whereDate('created_at', '<=', $request->date_to);
                })
                ->when($request->search && $request->date_from && $request->date_to, function ($query) use($request) {
                    $query->where('name', 'LIKE', '%'.$request->search.'%')
                            ->orWhere('email', 'LIKE', '%'.$request->search.'%')
                            ->whereDate('created_at', '>=', $request->date_from)
                            ->whereDate('created_at', '<=', $request->date_to);
                })
                ->orderBy('created_at', 'desc')
                ->paginate($request->limit ? $request->limit : User::count());
        
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->all());

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        
        return response(null, 204);
    }

    /**
     * Login the specified resource from storage.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::with('profile')->find($request->user()->id);

        $user->update([
            'logged_in_at' => Carbon::now()
        ]);
        
        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Logout the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        // Get the access token for the current user
        $access_token = Auth::user()->token();

        // Retrieve the access token ID
        $token_id = $access_token->id;

        $token_repository = app(TokenRepository::class);
        $refresh_token_repository = app(RefreshTokenRepository::class);
        
        // Revoke an access token...
        $token_repository->revokeAccessToken($token_id);
        
        // Revoke all of the token's refresh tokens...
        $refresh_token_repository->revokeRefreshTokensByAccessTokenId($token_id);

        return response(null, 204);
    }

    /**
     * Forgot the specified resource from storage.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|exists:users,email',
        ]);

        PasswordResetToken::where('email', $request->email)->delete();
        
        $password_reset_token = PasswordResetToken::create([
            'email' => $request->email,
            'token' => Str::random(64),
            'created_at' => Carbon::now()
        ]);

        $name = User::where('email', $request->email)->first()->name;

        Mail::to($request->email)->send(new ResetPassword($name, $password_reset_token->token));
    }

    /**
     * Reset password the specified resource from storage.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        $validated_data = $request->validate([
            'password' => 'required|min:8|max:255',
            'token' => 'required|max:255|exists:password_reset_tokens,token',
        ]);

        $email = PasswordResetToken::where('token', $request->token)->first()->email;
        
        $user = User::where('email', $email)->first();
        $user->update($validated_data);

        PasswordResetToken::where('email', $email)->delete();
    }
}
