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
        $count = DB::table('keys')->count();
        return response()->json(['count' => $count], 200);
    }
    public function all()
    {

        $data = $this->keyRepository->keyLists();
        return response()->json(['keys' => $data], 200);
    }
    public function create()
    {

        $data = $this->keyRepository->createKey();
        return response()->json(['data' => $data], 200);
    }
    public function delete()
    {

        $this->keyRepository->deleteKey();
        return response()->json(['message' => "successfully"], 200);
    }
}