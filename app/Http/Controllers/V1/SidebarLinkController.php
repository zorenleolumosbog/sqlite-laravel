<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SidebarLinkRequest;
use App\Http\Resources\V1\SidebarLinkResource;
use App\Models\V1\SidebarLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SidebarLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sidebar_links = SidebarLink::
                orderBy('created_at', 'desc')
                ->paginate($request->limit ? $request->limit : SidebarLink::count());
        
        return SidebarLinkResource::collection($sidebar_links);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SidebarLinkRequest $request)
    {
        $file = $request->file('file_icon');
        $name = $file->hashName();

        $path = Storage::disk('public')->put('upload', $file);

        $sidebar_link = SidebarLink::create([
            'title' => $request->title,
            'link' => $request->link,
            'bg_color' => $request->bg_color,
            'name' => $name,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
            'size' => $file->getSize(),
        ]);

        return new SidebarLinkResource($sidebar_link);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SidebarLink  $sidebar_link
     * @return \Illuminate\Http\Response
     */
    public function show(SidebarLink $sidebar_link)
    {
        return new SidebarLinkResource($sidebar_link);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SidebarLink  $sidebar_link
     * @return \Illuminate\Http\Response
     */
    public function update(SidebarLinkRequest $request, SidebarLink $sidebar_link)
    {
        $file = $request->file('file_icon');
        if($file) {
            //Remove existing file
            Storage::disk('public')->delete($sidebar_link->path);

            //Update new file
            $name = $file->hashName();
    
            $path = Storage::disk('public')->put('upload', $file);
    
            $sidebar_link->update([
                'title' => $request->title,
                'link' => $request->link,
                'bg_color' => $request->bg_color,
                'name' => $name,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'size' => $file->getSize(),
            ]);
        } else {
            $sidebar_link->update([
                'title' => $request->title,
                'link' => $request->link,
                'bg_color' => $request->bg_color,
            ]);
        }

        return new SidebarLinkResource($sidebar_link);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SidebarLink $sidebar_link
     * @return \Illuminate\Http\Response
     */
    public function destroy(SidebarLink $sidebar_link)
    {
        Storage::disk('public')->delete($sidebar_link->path);
        
        $sidebar_link->delete();
        
        return response(null, 204);
    }
}
