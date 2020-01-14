<?php

namespace App\Repositories;

use App\Contracts\KeyContract;
use App\Key;
use Illuminate\Support\Facades\DB;

class KeyRepository implements KeyContract
{

    public function keyLists()
    {
        $keys = null;
        $searchkey = request()->key;
        $searchValue = request()->value;

        if (auth()->user()->role == 1) {
            if ($searchkey != null && $searchValue != null) {
                if ($searchkey == 'id' || $searchkey == 'user_id' || $searchkey == 'paid') {
                    $keys =  DB::table('keys')->join('users', 'users.id', '=', 'keys.user_id')->where(
                        "keys.{$searchkey}",
                        $searchValue
                    )->orderBy('id', 'desc')->offset(request()->start)->take(100)->select('keys.*', 'users.name as username')->get();
                } else {
                    $keys =  DB::table('keys')->join('users', 'users.id', '=', 'keys.user_id')->where(
                        "keys.{$searchkey}",
                        "LIKE",
                        "{$searchValue}%"
                    )->orderBy('id', 'desc')->offset(request()->start)->take(100)->select('keys.*', 'users.name as username')->get();
                }
            } else {
                $keys =  DB::table('keys')->join('users', 'users.id', '=', 'keys.user_id')->orderBy('id', 'desc')->offset(request()->start)->take(100)->select('keys.*', 'users.name as username')->get();
            }
        } else {
            $keys = DB::table('keys')->where('user_id', auth()->user()->id)->orderBy('id', 'desc')->offset(request()->start)->take(100)->get();
            if ($searchkey != null && $searchValue != null) {
                if ($searchkey == 'id' || $searchkey == 'user_id' || $searchkey == 'paid') {
                    $keys = DB::table('keys')->where('user_id', auth()->user()->id)->where(
                        "keys.{$searchkey}",
                        $searchValue
                    )->orderBy('id', 'desc')->offset(request()->start)->take(100)->get();
                } else {
                    $keys = DB::table('keys')->where('user_id', auth()->user()->id)->where(
                        "keys.{$searchkey}",
                        "LIKE",
                        "{$searchValue}%"
                    )->orderBy('id', 'desc')->offset(request()->start)->take(100)->get();
                }
            } else {
                $keys = DB::table('keys')->where('user_id', auth()->user()->id)->orderBy('id', 'desc')->offset(request()->start)->take(100)->get();
            }
        }
        return $keys;
    }
    public function createKey($key)
    {
        $key = Key::create($key);
        return $key;
    }
    public function updateKey()
    {
        $keys = request()->editData;
        DB::beginTransaction();
        try {
            foreach ($keys as  $key) {
                $key_id = $key['id'];
                if (auth()->user()->role == 1) {
                    $key = [
                        "module_serial" => $key['module_serial'],
                        "update_key" => $key['update_key'],
                        "tablet_key" => $key['tablet_key'],
                        "tabscreen_key" => $key['tabscreen_key'],
                        "admin_remark" => $key['admin_remark'],
                        "paid" => $key['paid'],
                        "hdd_serial" => $key['hdd_serial'],
                        "hardware_id" => $key['hardware_id'],

                    ];
                } else {
                    $key = [
                        "hdd_serial" => $key['hdd_serial'],
                        "hardware_id" => $key['hardware_id'],
                        "client_remark" => $key['client_remark'],
                        "paid" => $key['paid'],

                    ];
                }

                Key::find($key_id)->update($key);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function deleteKey()
    {
        $keys = request()->keys;
        DB::beginTransaction();
        try {
            foreach ($keys as  $key) {
                Key::find($key)->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}