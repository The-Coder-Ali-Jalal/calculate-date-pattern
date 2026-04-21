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
       Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('day_of_week', 15); 
            $table->unsignedTinyInteger('day_of_month'); 
            $table->string('year_range', 25); 
            $table->json('response_data'); 
            $table->unsignedInteger('matches_count')->default(0);
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_logs');
    }
};
