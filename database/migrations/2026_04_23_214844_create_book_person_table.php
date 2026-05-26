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
        if (!Schema::hasTable('book_person')) {
            Schema::create('book_person', function (Blueprint $table) {
                $table->bigIncrements('id');
    
                // 🔗 FK → books
                $table->foreignId('book_id')
                    ->constrained()
                    ->cascadeOnDelete();
    
                // 🔗 FK → people
                $table->foreignId('person_id')
                    ->constrained()
                    ->cascadeOnDelete();
    
                // 🎯 role (author, translator)
                $table->enum('role', ['author', 'translator']);
    
                $table->timestamps();
    
                 // 🔥 منع التكرار
                $table->unique(['book_id', 'person_id', 'role']);
            });
        }
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_people');
    }
};
