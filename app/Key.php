<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Key extends Model
{
    protected $fillable = [
        "module_serial",
        "hdd_serial",
        "hardware_id",
        "update_key",
        "tablet_key",
        "tabscreen_key",
        "client_remark",
        "admin_remark",
        "paid",
        "user_id"
    ];
    public function user()
    {
        return  $this->belongsTo('App\User');
    }
}