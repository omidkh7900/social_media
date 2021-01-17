<?php


namespace App\Services\Interfaces;


interface createServiceInterface
{
    public function create(array $arguments);

    public function update(string $slug ,array $arguments);
}
