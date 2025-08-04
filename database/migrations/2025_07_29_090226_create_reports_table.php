<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adv_id');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('adv_id')->references('id')->on('advs');
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('type', ['spam', 'fraud', 'inappropriate_content', 'wrong_category', 'already_sold', 'misleading_information', 'duplicate', 'other']); // must be same values in front-end
            $table->string('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
