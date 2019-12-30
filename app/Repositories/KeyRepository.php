<?php

namespace App\Repositories;

use App\Contracts\KeyContract;
use App\Key;

class KeyRepository implements KeyContract
{

    public function keyLists()
    {
        $keys = Key::all();
        return $keys;
    }
    public function createKey()
    {
        $key = Key::create(request()->all());
        return $key;
    }
    public function updateKey($key)
    {
        $key = $key->update(request()->all());
        return $key;
    }
    public function deleteKey($key)
    {
        return $key->delete();
    }
}