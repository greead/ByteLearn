<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Set attributes that should be searchable by Meilisearch
     * 
     * @return array
     */
    public function toSearchableArray(): array {
        return [
            "username" => $this->username,
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_until' => 'datetime',
            'permanently_banned_at' => 'datetime',
        ];
    }

    /**
     * The users' interests (tags internally).
     */
    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'user_interest');
    }

    /**
     * The videos the user owns.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * The user's tokens.
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }

    /**
     * The strikes on the user's accounts.
     */
    public function userStrikes(): HasMany
    {
        return $this->hasMany(UserStrike::class);
    }

    /**
     * The videos that the user has liked.
     */
    public function videosLiked(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'user_video_like');
    }

    /**
     * The videos that the user has watched.
     */
    public function videosWatched(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'user_watched_video');
    }

    /**
     * The users this user follows.
     */
    public function usersFollows(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follows', 'follower_id', 'creator_id');
    }

    /**
     * The followers of this user.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follows', 'creator_id', 'follower_id');
    }

    /**
     * The video reports the user has made.
     */
    public function reports(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'video_reports');
    }

    /**
     * The comments the user has made.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The comments that the user liked.
     */
    public function commentsLiked(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'user_comment_like');
    }

    /**
     * The playlists that the user owns.
     */
    public function playlists(): HasMany
    {
        return $this->hasMany(Playlist::class);
    }
}
