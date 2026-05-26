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
        if (!Schema::hasTable('books')) {
            Schema::create('books', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title');             
    
                $table->foreignId('section_id')    
                    ->nullable()
                    ->constrained()
                    ->restrictOnDelete();
    
                    $table->foreignId('company_id')     
                    ->nullable()
                    ->constrained()
                    ->restrictOnDelete();
    
                $table->text('description')->nullable();
                $table->boolean('active')->default(1); 
                $table->timestamps();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
