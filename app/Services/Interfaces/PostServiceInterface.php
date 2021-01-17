<?php


namespace App\Services\Interfaces;


interface PostServiceInterface extends deleteServiceInterface, createServiceInterface
{
    public function find(string $slug);

    public function all(string $username);

    public function saveFiles(array $paths);

    public function like(string $slug,$username);

    public function savePost(string $slug,$username);

    public function savedPosts($username);

    public function tagsPosts($tagId);

    public function mentionsPosts($username);

    public function commentsPosts(string $slug);

    public function findMentions($value);

    public function findTags($value);
}
