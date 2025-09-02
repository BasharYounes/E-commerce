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
        Schema::create('authunticate_requests', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('user_id'); // المستخدم الذي قدم الطلب
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // حالة الطلب
            $table->string('document_path'); // مسار ملف الوثائق
            $table->text('rejection_reason')->nullable(); // سبب الرفض (إذا تم رفض الطلب)
            $table->timestamp('processed_at')->nullable(); // وقت معالجة الطلب
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authunticate_requests');
    }
};
