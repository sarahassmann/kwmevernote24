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
        Schema::create('kwmlists', function (Blueprint $table) {
            $table->id();   // id is primary-key, AUTO INCREMENTED
            $table->string('listName')->unique();  // listName should be unique -> no double listed names
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // FK to users-table for later identification of user
            // using useCurrent() to set the current timestamp as default value
            $table->timestamp('created_at')->useCurrent();
            // updated_at can be null, because it is not set on creation
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwmlists');
    }
};
