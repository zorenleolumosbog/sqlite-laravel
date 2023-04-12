<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserProfileRequest;
use App\Http\Resources\V1\UserProfileResource;
use App\Models\V1\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_profiles = UserProfile::
                orderBy('created_at', 'desc')
                ->paginate($request->limit ? $request->limit : UserProfile::count());
        
        return UserProfileResource::collection($user_profiles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserProfileRequest $request)
    {
        $user_profile = UserProfile::create($request->all());

        return new UserProfileResource($user_profile);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserProfile  $user_profile
     * @return \Illuminate\Http\Response
     */
    public function show(UserProfile $user_profile)
    {
        return new UserProfileResource($user_profile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserProfile  $user_profile
     * @return \Illuminate\Http\Response
     */
    public function update(UserProfileRequest $request, UserProfile $user_profile)
    {
        $user_profile->update($request->all());

        return new UserProfileResource($user_profile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserProfile  $user_profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserProfile $user_profile)
    {
        $user_profile->delete();
        
        return response(null, 204);
    }
}
