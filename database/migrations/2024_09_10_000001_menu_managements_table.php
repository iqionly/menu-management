<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_management_list_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->references('id')->on('menu_management_list_menus');
            $table->integer('priority', false, true)->nullable();
            $table->string('name', 50);
            $table->string('icons_path', 50)->nullable();
            $table->integer('depth')->default(0);
            $table->tinyText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['parent_id', 'priority'], 'menus_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_management_list_menus');
    }
};
