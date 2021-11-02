<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Bouncer;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postForm ($id = null) {
        /** @var User $user */
        $user = Auth::user();

        $post = null;
        if ($id) {
            /** @var Post $post */
            $post = Post::query()->find($id);
            if (!$post || ($post->user->id !== $user->id && !$user->can('edit', $post) && !$user->can('view', $post))) {
                return response()->redirectTo('/');
            }
        }

        return view('post-form', ['post' => $post]);
    }

    public function savePost (Request $request, $id = null) {
        /** @var User $user */
        $user = Auth::user();

        Validator::validate($request->all(), [
            'title' => 'required|min:1',
            'content' => 'required|min:1'
        ]);

        //all valid, validate id if not null
        /** @var Post $post */
        if ($id) {
            $post = Post::query()->find($id);
            if (!$post || ($post->user->id !== $user->id && !$user->can('edit', $post) && !$user->can('view', $post))) {
                return back()->withErrors(['post' => __('Post does not exist')]);
            }
        } else {
            $post = new Post();
        }

        //set data
        $post->title = $request->get('title');
        $post->content = $request->get('content');
        if (!$post->user) {
            $post->user()->associate($user);
        }
        $post->save();

        if (!$id) {
            Bouncer::allow($user)->toManage($post);
        }

        return response()->redirectToRoute('post.form', ['id' => $post->id]);
    }

    public function accessForm ($id, $type) {
        /** @var User $user */
        $user = Auth::user();

        /** @var Post $post */
        $post = Post::query()->find($id);
        if (!$post || $post->user->id !== $user->id) {
            return response()->redirectTo('/');
        }

        //get all users
        $users = User::query()->where('id', '!=', $user->id)->get();

        return view('post-access', ['post' => $post, 'users' => $users, 'type' => $type]);
    }

    public function saveAccess (Request $request, $id, $type) {
        /** @var User $user */
        $user = Auth::user();

        /** @var Post $post */
        $post = Post::query()->find($id);
        if (!$post || $post->user->id !== $user->id) {
            return response()->redirectTo('/');
        }

        $users = $request->get('users', []);
        $disallowedUserNotIn = $users;
        $disallowedUserNotIn[] = $user->id;
        //disallow users not checked
        $disallowedUsers = User::query()->whereNotIn('id', $disallowedUserNotIn)->get();

        /** @var User $disallowedUser */
        foreach ($disallowedUsers as $disallowedUser) {
            $disallowedUser->disallow($type, $post);
        }

        //allow checked users
        $allowedUsers = User::query()->whereIn('id', $users)->get();

        /** @var User $allowedUser */
        foreach($allowedUsers as $allowedUser) {
            $allowedUser->allow($type, $post);
        }

        return back();
    }
}
