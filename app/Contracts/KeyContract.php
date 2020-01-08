<?php

namespace App\Contracts;

interface KeyContract
{
    public function keyLists();
    public function createKey();
    public function updateKey();
    public function deleteKey();
}