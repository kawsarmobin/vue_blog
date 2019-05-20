<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->expectsJson()) {
            return response()->json(Post::orderBy('title')
                                                ->with(['category', 'tag'])
                                                ->get());
        }
        return view('admin.posts.index')
                    ->with('categories', Category::orderBy('name')->get())
                    ->with('tags', Tag::orderBy('name')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->postStoreValidate();

        $file = $request->attachment['file'];
        
        $file = str_replace(explode(',', $file)[0].',', '', $file);
        $file = str_replace(' ', '+', $file);

        $file = base64_decode($file);
        $safeName = str_random(10).'.'.$request->attachment['type'];
        $success = file_put_contents(public_path().Post::ATTACHMENT_PATH.'/'.$safeName, $file);

        $request['attachment'] = $safeName;
        $request['user_id'] = Auth::user()->id;
        $request['category_id'] = $request->category;
        $request['tag_id'] = $request->tag;

        Post::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->postUpdateValidate();

        if ($request->attachment['file']) {
            $file = $request->attachment['file'];
        
            $file = str_replace(explode(',', $file)[0].',', '', $file);
            $file = str_replace(' ', '+', $file);

            $file = base64_decode($file);
            $safeName = str_random(10).'.'.$request->attachment['type'];
            file_put_contents(public_path().Post::ATTACHMENT_PATH.'/'.$safeName, $file);
            
            $request['attachment'] = $safeName;

            unlink(public_path().Post::ATTACHMENT_PATH.'/'.$post->attachment);
        } else {
            $request['attachment'] = $post->attachment;
        }

        $request['user_id'] = Auth::user()->id;
        $request['category_id'] = $request->category;
        $request['tag_id'] = $request->tag;

        $post->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        unlink(public_path().Post::ATTACHMENT_PATH.'/'.$post->attachment);
    }

    protected function postStoreValidate()
    {
        return $this->validate(request(), [
            'category' => 'required',
            'tag' => 'required',
            'title' => 'required',
            'description' => 'required',
            'attachment.file' => 'required'
        ], [
            'attachment.file.required' => 'The attachment field is required.'
        ]);
    }

    protected function postUpdateValidate()
    {
        return $this->validate(request(), [
            'category' => 'required',
            'tag' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);
    }
}
