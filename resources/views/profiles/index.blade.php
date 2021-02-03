@extends('layouts/app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-3 p-5">
      <img src="{{ $user->profile->profileImage() }}" class="rounded-circle" height="150px" width="150px">
    </div>
    <div class="col-9 pt-5">
      <div class="d-flex justify-content-between align-items-baseline">
        <div class="d-flex align-items-center pb-0">
          <div class="h2">{{ $user->username }}</div>
          <follow-button
            user-id="{{ $user->id }}"
            follows="{{ $follows }}"
          ></follow-button>
        </div>
      </div>

      @can('update', $user->profile)
      <div>
        <i data-feather="edit"  height="18px" stroke-width="1px"></i>
        <a href="/profile/{{ $user->id }}/edit">Edit Profile</a>
        @can('update', $user->profile)
         <span class="border-right mx-3"></span>
         <i data-feather="plus-square" height="18px" stroke-width="1px"></i> <a href="/p/create">Add New Post</a>
        @endcan
      </div>
      @endcan

      <div class="d-flex py-2">
        <div class="pr-4"><strong>{{ $numPosts }}</strong> posts</div>
        <div class="pr-4"><strong>{{ $numFollowers }}</strong> followers</div>
        <div class="pr-4"><strong>{{ $numFollowings }}</strong> following</div>
      </div>
      <div class="pt-1 font-weight-bold">{{ $user->profile->title }}</div>
      <div class="pt-1">{{ $user->profile->description }}</div>
      <div class="pt-1"><a href="#">{{ $user->profile->url }}</a></div>
    </div>
  </div>
  <div class="row pt-5">
   @foreach($user->posts as $post)
    <div class="col-4 pb-4">
      <a href="/p/{{ $post->id }}">
        <img
          src="/storage/{{ $post->image }}"
          alt="{{ $post->title }}"
          class="w-100 border rounded"
        >
      </a>
    </div>
   @endforeach
  </div>
</div>
@endsection
