<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-menu-manager.table_prefix', 'fmm_');

        Schema::create($prefix . 'menus', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->foreignId('menu_location_id')
                  ->constrained($prefix . 'menu_locations')
                  ->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $prefix = config('filament-menu-manager.table_prefix', 'fmm_');
        Schema::dropIfExists($prefix . 'menus');
    }
};
