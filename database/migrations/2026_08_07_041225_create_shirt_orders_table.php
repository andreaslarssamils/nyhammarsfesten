<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shirt_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();

            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();

            $table->string('model');                        // unisex, dam, barn
            $table->string('color');                        // svart, vit
            $table->string('size');                         // XS-XXL eller 110-158
            $table->unsignedTinyInteger('quantity')->default(1);
            $table->unsignedInteger('unit_price');          // öre-fritt, hela kronor

            $table->text('note')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('collected_at')->nullable();

            $table->ipAddress('ip')->nullable();
            $table->timestamps();

            $table->index(['model', 'color', 'size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shirt_orders');
    }
};
