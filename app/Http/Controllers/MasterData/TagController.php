<?php

namespace App\Http\Controllers\MasterData;

use App\DataTables\MasterData\TagDataTable;
use App\Http\Controllers\Controller;
use App\Models\MasterData\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TagDataTable $tagDataTable)
    {
        return $tagDataTable->render('pages.master-data.tag');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.master-data.tag-form',[
            'data' => new Tag(),
            'action' => route('master-data.tags.store')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Tag $tag)
    {
        $tag->fill($request->only(['name']));
        $tag->slug = \Illuminate\Support\Str::slug($request->name);
        $tag->save();

        return responseSuccess();
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return view('pages.master-data.tag-form',[
            'data' => $tag,
            'action' => null
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('pages.master-data.tag-form',[
            'data' => $tag,
            'action' => route('master-data.tags.update', $tag->id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $tag->fill($request->only(['name']));
        $tag->save();

        return responseSuccess(true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        {
            $tag->delete();

            return responseSuccessDelete();
        }
    }
}
