<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // function to create the kwmtags table
    public function up(): void
    {
        Schema::create('kwmtags', function (Blueprint $table) {
            $table->id();
            $table->string('tagName')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwmtags');
    }
};
