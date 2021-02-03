<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(User $user)
    {
        $follows = User::isFollowing($user);

        // Cache number of posts
        $numPosts = Cache::remember(
          "num.posts.{$user->id}",
          now()->addHour(1),
          function () use ($user) {
            return $user->posts->count();
          }
        );
        // Cache number of followers
        $numFollowers = Cache::remember(
          "num.followers.{$user->id}",
          now()->addHour(1),
          function () use ($user) {
            return $user->profile->followers->count();
          }
        );
        // Cache number of followings
        $numFollowings = Cache::remember(
          "num.followings.{$user->id}",
          now()->addHour(1),
          function () use ($user) {
            return $user->following->count();
          }
        );

        return view(
            'profiles/index',
            compact('user', 'follows', 'numPosts', 'numFollowers', 'numFollowings')
        );
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);
        return view('profiles/edit', compact('user'));
    }

    /**
     * Update a profile
     */
    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        // Scale the image
        if (request('image')) {
            $imagePath = request('image')->store('uploads/profile', 'public');
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();
            $imageArr = ['image' => $imagePath];
        }
        auth()->user()->profile->update(array_merge(
            $data,
            $imageArr ?? []
        ));
        return redirect("profile/{$request->user()->id}");
    }
}
