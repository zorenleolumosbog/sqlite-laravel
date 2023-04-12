<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserWeeklyAttachmentRequest;
use App\Http\Resources\V1\UserWeeklyAttachmentResource;
use App\Models\V1\Option;
use App\Models\V1\UserWeeklyAttachment;
use Illuminate\Http\Request;

class UserWeeklyAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_weekly_attachments = UserWeeklyAttachment::
                when($request->user_id, function ($query) use($request) {
                    $query->where('user_id', $request->user_id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate($request->limit ? $request->limit : UserWeeklyAttachment::count());
        
        return UserWeeklyAttachmentResource::collection($user_weekly_attachments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserWeeklyAttachmentRequest $request)
    {
        $user_weekly_attachment = UserWeeklyAttachment::create($request->all());

        return new UserWeeklyAttachmentResource($user_weekly_attachment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserWeeklyAttachment  $user_weekly_attachment
     * @return \Illuminate\Http\Response
     */
    public function show(UserWeeklyAttachment $user_weekly_attachment)
    {
        return new UserWeeklyAttachmentResource($user_weekly_attachment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserWeeklyAttachment  $user_weekly_attachment
     * @return \Illuminate\Http\Response
     */
    public function update(UserWeeklyAttachmentRequest $request, UserWeeklyAttachment $user_weekly_attachment)
    {
        $start_datetime = Option::where('name', 'start_datetime')->first()?->value;
        $end_datetime = Option::where('name', 'end_datetime')->first()?->value;

        $status = UserWeeklyAttachment::when($start_datetime && $end_datetime, function ($query) use($start_datetime, $end_datetime) {
                    $query->whereDate('created_at', '>=', $start_datetime)
                            ->whereDate('created_at', '<=', $end_datetime);
                })
                ->find($user_weekly_attachment->id);

        if(!$status) {
            return response()->json([
                'message' => "The post is locked.",
                "errors" => [
                    "weekly_post" => [
                        "The post is locked."
                    ]
                ]
            ], 422);
        }
                
        $user_weekly_attachment->update($request->all());

        return new UserWeeklyAttachmentResource($user_weekly_attachment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserWeeklyAttachment $user_weekly_attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserWeeklyAttachment $user_weekly_attachment)
    {
        $user_weekly_attachment->delete();
        
        return response(null, 204);
    }
}
