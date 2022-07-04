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
        Schema::create('proposal', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('title');
            $table->integer('cost');
            $table->text('document')->nullable();
            $table->integer('review_id')->default(0);
            $table->text('review_comment')->nullable();
            $table->integer('approve1_id')->default(0);
            $table->text('approve1_comment')->nullable();
            $table->integer('approve2_id')->default(0);
            $table->text('approve2_comment')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('proposal');
    }
};
