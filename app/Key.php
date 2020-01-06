<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        "paid"
    ];
}