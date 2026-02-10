<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar campos solo si no existen
            if (!Schema::hasColumn('users', 'surname')) {
                $table->string('surname')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')->nullable()->after('password')->constrained('roles')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'type_document')) {
                $table->string('type_document', 20)->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'n_document')) {
                $table->string('n_document', 20)->nullable()->after('type_document');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->tinyInteger('gender')->nullable()->after('n_document');
            }
            if (!Schema::hasColumn('users', 'state')) {
                $table->tinyInteger('state')->default(1)->after('gender');
            }
            // Solo agregar deleted_at si no existe
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
            }

            $columnsToCheck = [
                'surname', 'role_id', 'phone', 'avatar',
                'type_document', 'n_document', 'gender', 'state'
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
