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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();                       // كود الكوبون
            $table->enum('discount_type', ['percentage', 'fixed']); // نوع الخصم
            $table->float('discount_value');                        // قيمة الخصم (٪ أو مبلغ)
            $table->integer('usage_limit')->nullable();             // عدد المرات الممكن استخدامه
            $table->integer('used_times')->default(0);              // عدد المرات المستخدمة
            $table->timestamp('expires_at')->nullable();            // تاريخ الانتهاء
            $table->boolean('is_active')->default(true);            // هل الكوبون مفعل؟
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
