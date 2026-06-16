<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bsdkv1_settings')) {
            Schema::create('bsdkv1_settings', function (Blueprint $table) {
                $table->id();
                $table->string('setting_key')->unique();
                $table->text('setting_value')->nullable();
                $table->timestamps();

                $table->index('setting_key');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bsdkv1_settings');
    }
};
