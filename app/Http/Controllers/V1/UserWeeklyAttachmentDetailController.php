<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserWeeklyAttachmentDetailRequest;
use App\Http\Resources\V1\UserWeeklyAttachmentDetailResource;
use App\Models\V1\UserWeeklyAttachmentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserWeeklyAttachmentDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_weekly_attachment_details = UserWeeklyAttachmentDetail::
                orderBy('created_at', 'desc')
                ->paginate($request->limit ? $request->limit : UserWeeklyAttachmentDetail::count());
        
        return UserWeeklyAttachmentDetailResource::collection($user_weekly_attachment_details);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserWeeklyAttachmentDetailRequest $request)
    {
        $file = $request->file('file_weekly_photo');
        $name = $file->hashName();

        $path = Storage::disk('public')->put("upload/" . Carbon::now()->toDateString() . "/" . Auth::user()->id, $file);

        $user_weekly_attachment_detail = UserWeeklyAttachmentDetail::create([
            'user_weekly_attachment_id' => $request->user_weekly_attachment_id,
            'name' => $name,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
            'size' => $file->getSize(),
            'description' => $request->description
        ]);

        return new UserWeeklyAttachmentDetailResource($user_weekly_attachment_detail);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserWeeklyAttachmentDetail  $user_weekly_attachment_detail
     * @return \Illuminate\Http\Response
     */
    public function show(UserWeeklyAttachmentDetail $user_weekly_attachment_detail)
    {
        return new UserWeeklyAttachmentDetailResource($user_weekly_attachment_detail);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserWeeklyAttachmentDetail  $user_weekly_attachment_detail
     * @return \Illuminate\Http\Response
     */
    public function update(UserWeeklyAttachmentDetailRequest $request, UserWeeklyAttachmentDetail $user_weekly_attachment_detail)
    {
        $file = $request->file('file_weekly_photo');
        if($file) {
            //Remove existing file
            Storage::disk('public')->delete($user_weekly_attachment_detail->path);

            //Update new file
            $name = $file->hashName();
    
            $path = Storage::disk('public')->put("upload/" . Carbon::now()->toDateString() . "/" . Auth::user()->id, $file);
    
            $user_weekly_attachment_detail->update([
                'name' => $name,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'size' => $file->getSize(),
                'description' => $request->description
            ]);
        } else {
            $user_weekly_attachment_detail->update([
                'description' => $request->description
            ]);
        }

        return new UserWeeklyAttachmentDetailResource($user_weekly_attachment_detail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserWeeklyAttachmentDetail $user_weekly_attachment_detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserWeeklyAttachmentDetail $user_weekly_attachment_detail)
    {
        Storage::disk('public')->delete($user_weekly_attachment_detail->path);
        
        $user_weekly_attachment_detail->delete();
        
        return response(null, 204);
    }
}
