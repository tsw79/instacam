<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use function PHPUnit\Framework\equalToWithDelta;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = auth()->user()->following()->pluck('profiles.user_id');
        $posts = Post::whereIn('user_id', $users)
            ->with('user')  // load the relationship to avoid limit 1 in sql statement
            ->latest()      // order by the created by DESC
            ->paginate(3);
        return view('posts/index', compact('posts'));
    }

    public function show(\App\Models\Post $post)
    {
        $follows = User::isFollowing($post->user);
        return view(
          'posts/show',
          compact('post', 'follows')
        );
    }

    public function create()
    {
        return view('posts/create');
    }

    /**
     * Adds a post to the db
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        $imagePath = request('image')->store('uploads', 'public');
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
        $image->save();

        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image'   => $imagePath
        ]);

        return redirect('profile/' . auth()->user()->id);
    }
}
