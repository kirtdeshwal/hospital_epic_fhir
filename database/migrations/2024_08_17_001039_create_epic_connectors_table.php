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
        Schema::create('epic_connectors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->longText('access_token');
            $table->longText('refresh_token');
            $table->dateTime('expires_on');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epic_connectors');
    }
};
