<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('craft_id')->nullable()->constrained('crafts')->nullOnDelete();
            $table->string('craft_type', 100);               // display label: الصدف, السيرما, etc.
            $table->string('location');                       // ساقية المنقدي, شما - اشمون
            $table->string('owner');                          // مالك الورشة
            $table->string('workers_count', 50);              // string to support ranges like "15-20"
            $table->string('phone', 100);                     // supports multi-number "01007864568 / 01551304612"
            $table->decimal('latitude', 10, 7);               // GPS lat
            $table->decimal('longitude', 10, 7);              // GPS lon
            $table->text('short_description')->nullable();     // brief intro for show page
            $table->longText('content')->nullable();           // CKEditor rich HTML
            $table->string('cover_image')->nullable();         // storage path
            $table->boolean('is_active')->default(true);       // soft visibility toggle
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
