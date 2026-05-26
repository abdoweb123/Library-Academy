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
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type');
            $table->string('input_type')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();

            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
