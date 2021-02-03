<?php

namespace App\Models;

use App\Mail\WelcomeMailNewUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class User extends Authenticatable implements Searchable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created( function($user) {
            $user->profile()->create([
                'title' => $user->username,
            ]);
            Mail::to($user->email)->send(new WelcomeMailNewUser());
        });
    }

    public function getSearchResult(): SearchResult
    {
       $url = route('profile.show', $this->id);
       return new SearchResult($this, $this->name, $url);
    }

    /**
     * Profile belonging to a particular User
     *
     * @return Profile
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Posts
     *
     * @return array
     */
    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }

    public function following()
    {
        return $this->belongsToMany(Profile::class);
    }

    /**
     * Check if User is following a particular user
     *
     * @param User
     * @return boolean Returns true if the current auth user is following the given user, otherwise false
     */
    public static function isFollowing(User $user)
    {
        return (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
    }
}
