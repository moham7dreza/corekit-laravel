<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-menu-manager.table_prefix', 'fmm_');

        Schema::create($prefix . 'menu_locations', function (Blueprint $table) {
            $table->id();
            $table->string('handle')->unique();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $prefix = config('filament-menu-manager.table_prefix', 'fmm_');
        Schema::dropIfExists($prefix . 'menu_locations');
    }
};
