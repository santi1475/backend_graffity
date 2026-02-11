<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_icons_to_categories_and_brands.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Modificar Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon_name')->default('Package')->after('title');
            $table->string('imagen')->nullable()->change(); // Hacemos nullable la columna existente
        });

        // 2. Modificar Brands
        Schema::table('brands', function (Blueprint $table) {
            $table->string('icon_name')->default('Badge')->after('name');
            $table->string('image')->nullable()->change(); // Hacemos nullable la columna existente
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('icon_name');
            // No podemos revertir fácilmente nullable a not null sin saber si hay nulls
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('icon_name');
        });
    }
};
