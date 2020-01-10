<?php

namespace App\Http\Controllers;

use App\Contracts\KeyContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class KeyController extends Controller
{
    private $keyRepository;
    public function __construct(KeyContract $keyRepository)
    {
        $this->keyRepository = $keyRepository;
    }
    public function countData()
    {
        $count = null;
        if (auth()->user()->role == 1) {
            $count = DB::table('keys')->count();
        } else {
            $count = DB::table('keys')->where('user_id', auth()->user()->id)->count();
        }
        return response()->json(['count' => $count], 200);
    }
    public function all()
    {

        $data = $this->keyRepository->keyLists();
        return response()->json(['keys' => $data], 200);
    }
    public function create(Request $request)
    {
        $newKey = [];
        if (auth()->user()->role == 1) {
            $newKey = [
                "module_serial" => $request->module_serial,
                "update_key" => $request->update_key,
                "tablet_key" => $request->tablet_key,
                "tabscreen_key" => $request->tabscreen_key,
                "admin_remark" => $request->admin_remark,
                "paid" => $request->paid,
                "user_id" => $request->user_id
            ];
        } else {
            $newKey = [
                "hdd_serial" => $request->hdd_serial,
                "hardware_id" => $request->hardware_id,
                "client_remark" => $request->client_remark,
                "paid" => $request->paid,
                "user_id" => auth()->user()->id
            ];
        }

        $data = $this->keyRepository->createKey($newKey);
        return response()->json(['data' => $data], 200);
    }
    public function update()
    {
        // $keys = request()->editData;
        // foreach ($keys as  $key) {

        //     dd($key);
        // }
        // dd($keys);
        $this->keyRepository->updateKey();
        return response()->json(['message' => "successfully"], 200);
    }
    public function delete()
    {

        $this->keyRepository->deleteKey();
        return response()->json(['message' => "successfully"], 200);
    }
}