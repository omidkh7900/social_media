<?php


namespace App\Services\Interfaces;


interface deleteServiceInterface
{
    public function delete(int $id);

    public function forceDelete(int $id);

    public function restore(int $id);
}
