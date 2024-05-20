<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // table for kwmtodos with foreign keys to kwmlists and kwmnotes tables
    public function up(): void
    {
        Schema::create('kwmtodos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kwmlists_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kwmnotes_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('todoName');
            $table->string('todoDescription');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwmtodos');
    }
};
