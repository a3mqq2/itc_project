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
        Schema::table('medical_files', function (Blueprint $table) {
            $table->date('registration_date')->nullable()->change();
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->string('type')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('national_id')->nullable()->change();
            $table->string('registry_number')->nullable()->change();
            $table->date('dob')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_files', function (Blueprint $table) {
            $table->date('registration_date')->nullable(false)->change();
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->enum('type', ['husband', 'wife'])->change();
            $table->string('name')->nullable(false)->change();
            $table->string('national_id')->nullable(false)->change();
            $table->string('registry_number')->nullable(false)->change();
            $table->date('dob')->nullable(false)->change();
        });
    }
};
