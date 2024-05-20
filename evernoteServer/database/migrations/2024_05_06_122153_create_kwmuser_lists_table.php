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
        // adds a new table to the database called kwmuser_lists with two columns: user_id and kwmlists_id
        Schema::create('kwmuser_lists', function (Blueprint $table) {
            // foreignId creates a column with the name user_id and a foreign key constraint that references the id column on the users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kwmlists_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'kwmlists_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwmuser_lists');
    }
};
