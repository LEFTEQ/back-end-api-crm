<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index()
    {
        $tag = Tag::all();

        return response()->json([
            'tags' => $tag
        ],200);
    }

    /**
     * Store newly created resource in storage
     * @param Request $request
     * @return message
     */

    public function store(Request $request)
    {
        $this->validate($request, array(
            'name' => 'required',
        ));

        $tag = new Tag;
        $tag->name = $request->name;
        $tag->save();

        return response()->json([
            'message' => 'Successful'
        ],200);

    }

    /**
     * Display the specified resource
     * @param $id
     */
    public function show($id)
    {
        $tag = Tag::find($id);

    }

    /**
     * Update the specified resource
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);
        $this->validate($request, ['name' => 'required|max:255']);
        $tag->name = $request->name;
        $tag->save();

    }

    /**
     * Remove specified resource
     * @param $id
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        $tag->posts()->detach();
        $tag->delete();

    }
}
