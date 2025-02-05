<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('manage-post', function (User $user, Post $post) {
            return $user->id == $post->user_id || $user->role == User::ROLE_ADMIN;
        });

        Gate::define('manage-comment', function (User $user, Comment $comment) {
            return $user->id == $comment->user_id || $user->role == User::ROLE_ADMIN;
        });
    }
}
