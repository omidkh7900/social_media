<?php


namespace App\Services\Interfaces;


interface deleteServiceInterface
{
    public function delete(string $slug);

    public function forceDelete(string $slug);

    public function restore(string $slug);
}
