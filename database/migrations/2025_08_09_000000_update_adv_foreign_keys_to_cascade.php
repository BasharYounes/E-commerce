<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            if (Schema::hasColumn('likes', 'adv_id')) {
                $table->dropForeign(['adv_id']);
                $table->foreign('adv_id')->references('id')->on('advs')->onDelete('cascade');
            }
        });

        Schema::table('favorites', function (Blueprint $table) {
            if (Schema::hasColumn('favorites', 'adv_id')) {
                $table->dropForeign(['adv_id']);
                $table->foreign('adv_id')->references('id')->on('advs')->onDelete('cascade');
            }
        });

        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'adv_id')) {
                $table->dropForeign(['adv_id']);
                $table->foreign('adv_id')->references('id')->on('advs')->onDelete('cascade');
            }
        });

        Schema::table('evaluations', function (Blueprint $table) {
            if (Schema::hasColumn('evaluations', 'adv_id')) {
                $table->dropForeign(['adv_id']);
                $table->foreign('adv_id')->references('id')->on('advs')->onDelete('cascade');
            }
        });

        Schema::table('user_activities', function (Blueprint $table) {
            if (Schema::hasColumn('user_activities', 'adv_id')) {
                $table->dropForeign(['adv_id']);
                $table->foreign('adv_id')->references('id')->on('advs')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            if (Schema::hasColumn('likes', 'adv_id')) {
                $table->dropForeign(['adv_id']);
                $table->foreign('adv_id')->references('id')->on('advs');
            }
        });

        Schema::table('favorites', function (Blueprint $table) {
            if (Schema::hasColumn('favorites', 'adv_id')) {
                $table->dropForeign(['adv_id']);
                $table->foreign('adv_id')->references('id')->on('advs');
            }
        });

        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'adv_id')) {
                $table->dropForeign(['adv_id']);
                $table->foreign('adv_id')->references('id')->on('advs');
            }
        });

        Schema::table('evaluations', function (Blueprint $table) {
            if (Schema::hasColumn('evaluations', 'adv_id')) {
                $table->dropForeign(['adv_id']);
                $table->foreign('adv_id')->references('id')->on('advs');
            }
        });

        Schema::table('user_activities', function (Blueprint $table) {
            if (Schema::hasColumn('user_activities', 'adv_id')) {
                $table->dropForeign(['adv_id']);
                $table->foreign('adv_id')->references('id')->on('advs');
            }
        });
    }
};


