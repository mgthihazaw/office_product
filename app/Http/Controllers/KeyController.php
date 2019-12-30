<?php

namespace App\Http\Controllers;

use App\Contracts\KeyContract;
use Illuminate\Http\Request;

class KeyController extends Controller
{
    private $keyRepository;
    public function __construct(KeyContract $keyRepository)
    {
        $this->keyRepository = $keyRepository;
    }
    public function all()
    {
        $data = $this->keyRepository->keyLists();
        return response()->json(['count' => $data->count(), 'keys' => $data], 200);
    }
}