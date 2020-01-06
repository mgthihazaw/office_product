<?php

namespace App\Repositories;

use App\Contracts\KeyContract;
use App\Key;
use Illuminate\Support\Facades\DB;

class KeyRepository implements KeyContract
{

    public function keyLists()
    {

        $keys = DB::table('keys')->orderBy('id', 'desc')->offset(request()->start)->take(100)->get();
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
    public function deleteKey()
    {
        $keys = request()->keys;

        foreach ($keys as  $key) {
            Key::find($key)->delete();
        }
    }
}