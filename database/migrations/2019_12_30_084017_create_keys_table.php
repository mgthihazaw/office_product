<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('module_serial')->nullable();
            $table->string('hdd_serial')->nullable();
            $table->string('hardware_id')->nullable();
            $table->string('update_key')->nullable();
            $table->string('tablet_key')->nullable();
            $table->string('tabscreen_key')->nullable();
            $table->string('client_remark')->nullable();
            $table->string('admin_remark')->nullable();
            $table->boolean('paid')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keys');
    }
}