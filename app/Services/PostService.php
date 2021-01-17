<?php


namespace App\Services;


use App\Http\Resources\PostCollection;
use App\Models\Mention;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class PostService implements PostServiceInterface
{

    public function find(string $slug)
    {
        return (new PostCollection(Post::where('slug', $slug)->first()->toArray()))->toArray(request());
    }

    public function all(string $username)
    {
        return PostCollection::collection(User::where('username', $username)->first()->posts)->toArray(request());
    }

    public function saveFiles(array $files)
    {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = $file->store('posts');
        }
        return $paths;
    }

    public function create(array $arguments)
    {
        $paths = $this->saveFiles($arguments['files']);
        $post = new Post();
        if (isset($arguments['caption'])) {
            $post->caption = $arguments['caption'];
        }
        if (isset($arguments['commentable'])) {
            $post->comment_able = $arguments['commentable'];
        }
        $post->paths = implode(',', $paths);
        $post->user_id = $arguments['user_id'];
        $post->save();
        $mentions = $this->findMentions($arguments['caption']);
        $tags = $this->findTags($arguments['caption']);
        foreach ($mentions as $value) {
            $user = User::where('username', $value)->first();
            if (isset($user)) {
                $post->mentions->attach($user->id);
            }
        }
        foreach ($tags as $value) {
            $tag = Tag::where('name', $value)->first();
            if (isset($tag)) {
                $post->tags->attach($tag->id);
            }
        }
    }

    public function update($slug, array $arguments)
    {
        $post = Post::where('slug', $slug)->first();
        if (isset($arguments['caption'])) {
            $post->caption = $arguments['caption'];
        }
        if (isset($arguments['commentable'])) {
            $post->comment_able = $arguments['commentable'];
        }
        $post->save();
    }

    public function delete(string $slug)
    {
        Post::where('slug', $slug)->first()->delete();
    }

    public function forceDelete(string $slug)
    {
        Post::onlyTrashed()->where('slug', $slug)->first()->forceDelete();
    }

    public function restore(string $slug)
    {
        Post::onlyTrashed()->where('slug', $slug)->first()->restore();
    }

    public function like(string $slug, $username)
    {
        $user = User::where('username', $username);
        $postId = Post::where('slug', $slug)->first();
        $user->likedPosts->attach($postId);
    }

    public function savePost(string $slug, $username)
    {
        $user = User::where('username', $username);
        $postId = Post::where('slug', $slug)->first();
        $user->savedPosts->attach($postId->id);
    }

    public function savedPosts($username)
    {
        $user = User::where('username', $username);
        return PostCollection::collection($user->savedPosts);
    }

    public function tagsPosts($tagName)
    {
        $posts = Post::with('tag')->where('tag.name', $tagName)->get();
        return PostCollection::collection($posts);
    }

    public function mentionsPosts($username)
    {
        $user = User::where('username', $username);
        return PostCollection::collection($user->postsMentions);
    }

    public function findMentions($value)
    {
        $mentions = [];
        $write = false;
        for ($i = 0; $i < Str::length($value); $i++) {
            if ($value[$i] == '@') {
                $mentions[] = '';
                $write = true;
                continue;
            } else if ($value[$i] == ' ') {
                $write = false;
            }
            if ($write) {
                $mentions[count($mentions) - 1] .= $value[$i];
            }
        }
        return $mentions;
    }

    public function findTags($value)
    {
        $tags = [];
        $write = false;
        for ($i = 0; $i < Str::length($value); $i++) {
            if ($value[$i] == '#') {
                $tags[] = '';
                $write = true;
                continue;
            } else if ($value[$i] == ' ') {
                $write = false;
            }
            if ($write) {
                $tags[count($tags) - 1] .= $value[$i];
            }
        }
        return $tags;
    }

    public function commentsPosts(string $slug)
    {
        $post = Post::where('slug', $slug)->first();
        return $post->comments->toArray();
    }
}
