<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('git_integrations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('network_id')->constrained('git_networks');
            $table->foreignId('user_id')->constrained('users');
            $table->string('username')->nullable();
            $table->string('api_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('git_integrations');
    }
};
