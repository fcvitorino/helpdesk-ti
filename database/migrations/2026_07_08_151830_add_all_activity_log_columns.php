<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Adiciona todas as colunas que faltam
            if (!Schema::hasColumn('activity_log', 'log_name')) {
                $table->string('log_name')->nullable();
            }
            if (!Schema::hasColumn('activity_log', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('activity_log', 'subject_type')) {
                $table->string('subject_type')->nullable();
            }
            if (!Schema::hasColumn('activity_log', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable();
            }
            if (!Schema::hasColumn('activity_log', 'causer_type')) {
                $table->string('causer_type')->nullable();
            }
            if (!Schema::hasColumn('activity_log', 'causer_id')) {
                $table->unsignedBigInteger('causer_id')->nullable();
            }
            if (!Schema::hasColumn('activity_log', 'properties')) {
                $table->json('properties')->nullable();
            }
            if (!Schema::hasColumn('activity_log', 'batch_uuid')) {
                $table->uuid('batch_uuid')->nullable();
            }
            if (!Schema::hasColumn('activity_log', 'event')) {
                $table->string('event')->nullable();
            }
            // Adiciona índices para performance
            if (!Schema::hasIndex('activity_log', 'subject_type_subject_id_index')) {
                $table->index(['subject_type', 'subject_id']);
            }
            if (!Schema::hasIndex('activity_log', 'causer_type_causer_id_index')) {
                $table->index(['causer_type', 'causer_id']);
            }
            if (!Schema::hasIndex('activity_log', 'log_name_index')) {
                $table->index('log_name');
            }
        });
    }

    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn([
                'log_name', 'description', 'subject_type', 'subject_id',
                'causer_type', 'causer_id', 'properties', 'batch_uuid', 'event'
            ]);
        });
    }
};