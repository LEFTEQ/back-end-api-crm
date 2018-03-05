<?php

namespace App\Http\Controllers\Blog;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{

    /**
     * Display a listing of resource
     */
    public function index()
    {
        $category = Category::all();

        return response()->json([
            'categories' => $category
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

        $category = new Category;
        $category->name = $request->name;
        $category->save();

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
        $category = Category::find($id);

    }

    /**
     * Update the specified resource
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $this->validate($request, ['name' => 'required|max:255']);
        $category->name = $request->name;
        $category->save();

    }

    /**
     * Remove specified resource
     * @param $id
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->posts()->detach();
        $category->delete();

    }
}