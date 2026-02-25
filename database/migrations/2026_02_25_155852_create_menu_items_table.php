<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-menu-manager.table_prefix', 'fmm_');

        Schema::create($prefix . 'menu_items', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->foreignId('menu_id')
                  ->constrained($prefix . 'menus')
                  ->cascadeOnDelete();
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained($prefix . 'menu_items')
                  ->nullOnDelete();
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('target')->default('_self');
            $table->string('icon')->nullable();
            $table->string('type')->default('custom'); // custom | page | model
            $table->nullableMorphs('linkable');         // linkable_type, linkable_id
            $table->unsignedInteger('order')->default(0);
            $table->boolean('enabled')->default(true);
            $table->json('data')->nullable();           // arbitrary extra data
            $table->timestamps();

            $table->index(['menu_id', 'parent_id', 'order']);
        });
    }

    public function down(): void
    {
        $prefix = config('filament-menu-manager.table_prefix', 'fmm_');
        Schema::dropIfExists($prefix . 'menu_items');
    }
};
