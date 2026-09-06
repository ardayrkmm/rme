<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity('create', $model);
        });

        static::updated(function ($model) {
            static::logActivity('update', $model);
        });

        static::deleted(function ($model) {
            static::logActivity('delete', $model);
        });
    }

    protected static function logActivity(string $action, $model)
    {
        $userId = Auth::check() ? Auth::id() : null;
        $ip = Request::ip();
        $userAgent = Request::header('User-Agent');
        
        $modelClass = get_class($model);
        $className = class_basename($model);
        
        $description = "User " . ($userId ? "(ID: $userId)" : "Guest") . " performed {$action} on {$className}";

        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'model_type' => $modelClass,
            'model_id' => $model->id,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }
}
