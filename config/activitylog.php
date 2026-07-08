<?php

return [
    'log_name' => 'auditoria',

    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    'subject_returns_soft_deleted_models' => false,

    'default_log_name' => 'default',

    'delete_records_older_than_days' => 365,

    'model' => Spatie\Activitylog\Models\Activity::class,

    'table_name' => 'activity_log',

    'database_connection' => env('DB_CONNECTION', 'mysql'),
];