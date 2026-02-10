<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla companies
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('razon_social_comercial')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('n_document')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('ubigeo_region')->nullable();
            $table->string('ubigeo_provincia')->nullable();
            $table->string('ubigeo_distrito')->nullable();
            $table->string('region')->nullable();
            $table->string('provincia')->nullable();
            $table->string('distrito')->nullable();
            $table->text('address')->nullable();
            $table->string('urbanizacion')->nullable();
            $table->string('cod_local')->nullable();
            $table->timestamps();
        });

        // Tabla categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('imagen')->nullable();
            $table->tinyInteger('state')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla brands
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('image')->nullable();
            $table->smallInteger('state')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sku')->unique()->nullable();
            $table->string('barcode', 50)->nullable()->unique();
            $table->string('imagen')->nullable();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->decimal('price_general', 10, 2)->default(0);
            $table->decimal('price_company', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->tinyInteger('is_discount')->default(0);
            $table->decimal('max_discount', 5, 2)->default(0);
            $table->integer('disponiblidad')->default(1);
            $table->tinyInteger('state')->default(1);
            $table->tinyInteger('state_stock')->default(1);
            $table->string('unidad_medida')->nullable();
            $table->integer('stock')->default(0);
            $table->tinyInteger('include_igv')->default(1);
            $table->tinyInteger('is_icbper')->default(0);
            $table->tinyInteger('is_ivap')->default(0);
            $table->decimal('percentage_isc', 5, 2)->default(0);
            $table->tinyInteger('is_especial_nota')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['sku', 'barcode']);
        });

        // Agregar deleted_at a users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('companies');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
