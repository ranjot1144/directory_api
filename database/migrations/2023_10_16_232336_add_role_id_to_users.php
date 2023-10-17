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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id'); // Add the role_id column
            $table->foreign('role_id')->references('id')->on('roles'); // Create a foreign key relationship with the roles table
        }); 
    }
};
