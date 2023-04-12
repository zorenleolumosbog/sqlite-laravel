<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TelegramLinkRequest;
use App\Http\Resources\V1\TelegramLinkResource;
use App\Models\V1\TelegramLink;
use Illuminate\Http\Request;

class TelegramLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $telegram_links = TelegramLink::
                orderBy('created_at', 'desc')
                ->paginate($request->limit ? $request->limit : TelegramLink::count());
        
        return TelegramLinkResource::collection($telegram_links);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TelegramLinkRequest $request)
    {
        $telegram_link = TelegramLink::create($request->all());

        return new TelegramLinkResource($telegram_link);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TelegramLink  $telegram_link
     * @return \Illuminate\Http\Response
     */
    public function show(TelegramLink $telegram_link)
    {
        return new TelegramLinkResource($telegram_link);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TelegramLink  $telegram_link
     * @return \Illuminate\Http\Response
     */
    public function update(TelegramLinkRequest $request, TelegramLink $telegram_link)
    {
        $telegram_link->update($request->all());

        return new TelegramLinkResource($telegram_link);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TelegramLink $telegram_link
     * @return \Illuminate\Http\Response
     */
    public function destroy(TelegramLink $telegram_link)
    {
        $telegram_link->delete();
        
        return response(null, 204);
    }
}
