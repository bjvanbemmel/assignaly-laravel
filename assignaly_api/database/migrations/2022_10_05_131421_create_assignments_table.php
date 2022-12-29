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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('owner_id')->constrained('users');
            $table->string('title');
            $table->mediumText('description')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->integer('review')->nullable();
            $table->string('feedback')->nullable();
            $table->string('status')->default('Open');
            $table->string('remote_respository')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
};
