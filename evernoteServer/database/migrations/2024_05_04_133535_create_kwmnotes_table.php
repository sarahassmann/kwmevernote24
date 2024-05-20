<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // function for creating kwmnotes table with id, kwmlists_id, noteTitle, noteDescription and timestamps
    public function up(): void
    {
        Schema::create('kwmnotes', function (Blueprint $table) {
            // id is primary key
            $table->id();
            // foreign key kwmlists_id references id of kwmlists table
            $table->foreignId('kwmlists_id')->onDelete('cascade');
            $table->string('noteTitle')->nullable();
            $table->string('noteDescription')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwmnotes');
    }
};
