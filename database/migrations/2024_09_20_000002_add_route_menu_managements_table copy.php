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
        Schema::table('menu_management_list_menus', function (Blueprint $table) {
            $table->after('name', function($table) {
                $table->string('route', 50)->nullable();
            });
        });
    }

    public function down(): void
    {
        Schema::dropColumns('menu_management_list_menus', 'route');
    }
};
