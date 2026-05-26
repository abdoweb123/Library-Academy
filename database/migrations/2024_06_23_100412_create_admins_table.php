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
        Schema::create('admins', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->tinyInteger('super_admin')->default(0);
            $table->string('name');
            $table->string('email')->unique();
            $table->text('phone')->nullable();
            $table->text('image')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->text('password');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // Foreign Key
            $table->foreign('role_id')->references('id')
                ->on('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
