<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // table for many-to-many relationship between kwmtodos and kwmtags
    public function up(): void
    {
        Schema::create('kwmtodo_kwmtags', function (Blueprint $table) {
            // foreign keys to kwmtodos and kwmtags tables and primary key
            $table->foreignId('kwmtodos_id')->constrained()->onDelete('cascade');
            $table->foreignId('kwmtags_id')->constrained()->onDelete('cascade');
            $table->primary(['kwmtodos_id', 'kwmtags_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwmtodo_kwmtags');
    }
};
