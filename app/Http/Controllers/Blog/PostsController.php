<?php

namespace App\Http\Controllers\Blog;

use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Support\Facades\Storage;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::get();

        return response()->json([
            'posts' => $posts,
        ], 200);
    }

    public function store(Request $request)
    {
        // validate the data
        $this->validate($request, array(
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug',
        ));
        // store in the database
        $post = new Post;
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->body = $request->body;
        $post->published = $request->published;
        $post->author = $request->author;
        $post->category_id = $request->category_id;
        $post->featured_image = $request->featured_image;
        $post->save();

        $existingTags = $post->tags->pluck('name')->toArray();


        foreach($request->tags as $tag) {
            if(! ( in_array($tag, $existingTags) ) ) {
                $newTag = new Tag();
                $newTag->name = $tag;
                $newTag->save();
            }
        }

        $attachableTags = [];

        foreach($request->tags as $tag) {
            $attachableTags[] = Tag::where('name', $tag)->pluck('id')->first();
        }

        $post->tags()->sync($attachableTags,  false);

        return response()->json([
            'postId' => $post->id
        ], 200);
    }

    public function storeImage(Request $request)
    {

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();


            $path = Storage::putFile('public/blog', $image);
            //$post->image = $filename;
            return \response()->json([
                'image' => $image,
                'path' => $path,
            ], 200);
        }
        return \response()->json([
            'image' => 'Could not upload file'
        ], 404);
    }

    public function deleteImage(Request $request)
    {
        $path = $request->path;
        Storage::delete('public/' . $path);
        return response()->json([
            'Message' => 'Deleted',
        ]);

    }

    public function getPost($id)
    {
        $post = Post::find($id);
        $category = Category::where('id','=',$post->id)->get();
        $tags = $post->tags;

        return response()->json([
            'post' => $post,
            'tags' => $tags,
            'category' => $category
        ], 200);
    }

    public function updatePost(Request $request)
    {
        $id = $request->id;
        $post = Post::find($id);
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->body = $request->body;
        $post->published = $request->published;
        $post->author = $request->author;
        $post->category_id = $request->category_id;
        $post->featured_image = $request->featured_image;
        $post->save();


        if (isset($request->tags)) {
            $existingTags = Tag::pluck('name')->toArray();


            foreach($request->tags as $tag) {
                if(! ( in_array($tag, $existingTags) ) ) {
                    $newTag = new Tag();
                    $newTag->name = $tag;
                    $newTag->save();
                }
            }

            $attachableTags = [];

            foreach($request->tags as $tag) {
                $attachableTags[] = Tag::where('name', $tag)->pluck('id')->first();
            }

            $post->tags()->sync($attachableTags);
            return response()->json([
                'exisisi' => $existingTags,
                'Message' => $attachableTags,
            ], 200);
        } else {
            $post->tags()->sync(array());
        }

        return response()->json([
            'Message' => 'Successfully updated'
        ], 200);
    }
}
