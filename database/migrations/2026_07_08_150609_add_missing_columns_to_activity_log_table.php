<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Adiciona a coluna log_name (se não existir)
            if (!Schema::hasColumn('activity_log', 'log_name')) {
                $table->string('log_name')->nullable()->after('id');
            }
            // Adiciona a coluna subject_type (se não existir)
            if (!Schema::hasColumn('activity_log', 'subject_type')) {
                $table->string('subject_type')->nullable()->after('log_name');
            }
            // Adiciona a coluna subject_id (se não existir)
            if (!Schema::hasColumn('activity_log', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable()->after('subject_type');
            }
            // Adiciona a coluna causer_type (se não existir)
            if (!Schema::hasColumn('activity_log', 'causer_type')) {
                $table->string('causer_type')->nullable()->after('subject_id');
            }
            // Adiciona a coluna causer_id (se não existir)
            if (!Schema::hasColumn('activity_log', 'causer_id')) {
                $table->unsignedBigInteger('causer_id')->nullable()->after('causer_type');
            }
            // Adiciona a coluna properties (se não existir)
            if (!Schema::hasColumn('activity_log', 'properties')) {
                $table->json('properties')->nullable()->after('causer_id');
            }
        });
    }

    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn(['log_name', 'subject_type', 'subject_id', 'causer_type', 'causer_id', 'properties']);
        });
    }
};