<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Stevebauman\Location\Facades\Location;

class ModelObserver
{
    /**
     * Handle the "created" event.
     */
    public function created($model): void
    {
        $this->log('created', $model, null, $model->getAttributes());
    }

    /**
     * Handle the "updated" event.
     */
    // public function updated($model): void
    // {
    //     $this->log('updated', $model, $model->getOriginal(), $model->getChanges());
    // }
    public function updated($model): void
    {
        $changes = $model->getChanges();

        if (empty($changes)) {
            return; // skip noise
        }

        $this->log(
            'updated',
            $model,
            array_intersect_key($model->getOriginal(), $changes),
            $changes
        );
    }

    /**
     * Handle the "deleted" event.
     */
    public function deleted($model): void
    {
        $this->log('deleted', $model, $model->getOriginal(), null);
    }

    /**
     * Universal logger for model changes.
     */
    // protected function log(string $action, $model, $old = null, $new = null): void
    // {
    //     //  Prevent infinite recursion for AuditLog itself
    //     if ($model instanceof AuditLog) {
    //         return;
    //     }

    //     try {
    //         $user = Auth::user();
    //         $ip = Request::ip() ?? '127.0.0.1';
    //         $location = null;

    //         // OPTIMIZED: Removed blocking IP geolocation lookup
    //         // It was slow and called on every model change.
    //         // If needed, dispatch as async job instead.

    //         $oldValues = $old ? json_encode($old, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR) : null;
    //         $newValues = $new ? json_encode($new, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR) : null;

    //         AuditLog::create([
    //             // 'UserId' => auth()->id(),
    //             'UserId' => optional(Auth::user())->UserId,
    //             'Action' => $action,
    //             'AuditableType' => get_class($model),
    //             // 'AuditableId' => data_get($model, 'id'),
    //             'AuditableId' => $model->getKey(),
    //             'OldValues' => $oldValues,
    //             'NewValues' => $newValues,
    //             'IpAddress' => $ip,
    //             'Location' => $location,
    //         ]);

    //     } catch (\Throwable $e) {
    //         // Final fallback: do not crash the request
    //         report($e); // log the error in Laravel log
    //     }
    // }
    protected function log(string $action, $model, $old = null, $new = null): void
    {
        if ($model instanceof AuditLog) {
            return;
        }

        $user = Auth::user();

        $data = [
            'UserId' => optional($user)->UserId,
            'Action' => $action,
            'AuditableType' => get_class($model),
            'AuditableId' => $model->getKey(), // ✅ FIXED
            'OldValues' => $old ? json_encode($old) : null,
            'NewValues' => $new ? json_encode($new) : null,
            'IpAddress' => Request::ip(),
            'Location' => null,
        ];

        \DB::afterCommit(function () use ($data) {
            AuditLog::create($data);
        });
    }

}
