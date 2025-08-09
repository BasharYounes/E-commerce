<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('advs', 'adv_id')) {
            // Drop FK(s) on advs.adv_id if present, then drop the column
            $constraints = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'advs' AND COLUMN_NAME = 'adv_id' AND REFERENCED_TABLE_NAME IS NOT NULL");

            foreach ($constraints as $row) {
                $constraintName = $row->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE advs DROP FOREIGN KEY `{$constraintName}`");
            }

            Schema::table('advs', function (Blueprint $table) {
                $table->dropColumn('adv_id');
            });
        }
    }

    public function down(): void
    {
        // No-op: we don't want to reintroduce an unexpected self FK or column
    }
};


