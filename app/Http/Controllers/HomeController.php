<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        //get all posts
        $posts = Post::query()->where('user_id', $user->id);
        //get posts that the user is allowed to view or edit
        $postIds = [];
        $abilities = $user->getAbilities();
        /** @var \Silber\Bouncer\Database\Ability */
        foreach ($abilities as $ability) {
            $postIds[] = $ability->entity_id;
        }
        $posts = $posts->orWhereIn('id', $postIds)->get();
        return view('home', ['posts' => $posts]);
    }
}
