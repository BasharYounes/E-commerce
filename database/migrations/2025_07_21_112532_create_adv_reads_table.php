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
        Schema::create('adv_reads', function (Blueprint $table) {
            $table->id();
            $table->String('image');
            $table->decimal('price',10,2);
            $table->string('location');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('interactions_count')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('user_id')->references('id')->on('users');

            $table->index(['category_id', 'location', 'is_active', 'price']); 
            $table->index(['views_count','interactions_count']);
            $table->fullText('description');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adv-_reads');
    }
};
