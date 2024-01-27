<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $post = Post::with('author')->latest()->get();
        return view('showPost')->with('posts',$post)->withTitle('Latest posts!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->user()->can_post()) {
            return view('createPost');
        } else {
            return redirect('/')->withErrors('You do not have permission to do this!!!');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = new Post();
        $post->title = $request->get('title');
        $post->body = $request->get('body');
        $post->slug = $request->get('title');

        $duplicate = Post::where('slug',$post->slug)->first();
        if($duplicate)
        {
            return redirect('new-post')->withErrors('Title already exists.')->withInput();
        }

        $post->author_id = $request->user()->id;
        if($request->has('save'))
        {
            $post->active = 0;
            $message = 'Post saved successfully';
        }
        else
        {
            $post->active = 1;
            $message = 'Post published successfully';
        }
        $post->save();
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();

        if ($post) {
            if (!$post->active)
                return redirect('/')->withErrors('Page not found');
            $comments = $post->comments;
        } else {
            return redirect('/')->withErrors('Page not found');
        }
        return view('showPost')->withPost($post)->withComments($comments);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->first();
        if ($post && ($request->user()->id == $post->author_id || $request->user()->is_admin()))
            return view('editPost')->with('post', $post);
        else {
            return redirect('/')->withErrors('You do not have permission to do this!!!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $post_id = $request->input('post_id');
        $post = Post::find($post_id);
        if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
            $title = $request->input('title');
            $slug = $request->input('title');
            $duplicate = Post::where('slug', $slug)->first();
            if ($duplicate) {
                if ($duplicate->id != $post_id) {
                    return redirect('editPost/' . $post->slug)->withErrors('Title already exists.')->withInput();
                } else {
                    $post->slug = $slug;
                }
            }

            $post->title = $title;
            $post->body = $request->input('body');

            if ($request->has('save')) {
                $post->active = 0;
                $message = 'Post saved successfully';
                $landing = 'editPost/' . $post->slug;
            } else {
                $post->active = 1;
                $message = 'Post updated successfully';
                $landing = $post->slug;
            }
            $post->save();
            return view('home');
        } else {
            return redirect('/')->withErrors('You do not have permission to do this!!!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        //
        $post = Post::find($id);
        if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
            $post->delete();
            $data['message'] = 'Post deleted Successfully';
        } else {
            $data['errors'] = 'You do not have permission to do this!!!';
        }

        return redirect('/')->with($data);
    }
}
