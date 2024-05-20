<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // table for many-to-many relationship between kwmnotes and kwmtags
    public function up(): void
    {
        Schema::create('kwmnote_kwmtags', function (Blueprint $table) {
            // foreign keys to kwmnotes and kwmtags tables and primary key
            $table->foreignId('kwmnotes_id')->constrained()->onDelete('cascade');
            $table->foreignId('kwmtags_id')->constrained()->onDelete('cascade');
            $table->primary(['kwmnotes_id', 'kwmtags_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwmnote_kwmtags');
    }
};
