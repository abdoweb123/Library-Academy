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
        if (!Schema::hasTable('book_copies')) {
            Schema::create('book_copies', function (Blueprint $table) {
                $table->bigIncrements('id');
    
                $table->foreignId('book_id')
                    ->constrained()
                    ->restrictOnDelete();
    
                $table->foreignId('shelf_id')
                ->nullable()
                ->after('book_id')
                ->constrained()
                ->restrictOnDelete();


                $table->foreignId('publisher_id')       
                ->nullable()
                ->constrained()
                ->restrictOnDelete();

                
                $table->integer('pages')->nullable();          // عدد الصفحات
                $table->string('size')->nullable();            // مقاس الكتاب
                $table->integer('volumes_number')->default(1);  // عدد المجلدات
                $table->integer('publish_year')->nullable();  
                $table->string('general_number');       
                $table->string('classification_number')->nullable(); 
                $table->string('shelf_order')->nullable();        
                $table->string('book_code');            
               
                $table->enum('status', ['available', 'borrowed', 'damaged'])
                    ->default('available');
    
                $table->date('purchase_date')->nullable();
                $table->decimal('price', 10, 2)->nullable();
    
                $table->unique(['book_id', 'general_number']);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_copies');
    }
};
