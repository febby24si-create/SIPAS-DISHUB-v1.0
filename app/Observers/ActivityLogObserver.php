<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $this->log($model, 'created', 'Membuat data ' . class_basename($model));
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        $this->log($model, 'updated', 'Memperbarui data ' . class_basename($model));
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->log($model, 'deleted', 'Menghapus data ' . class_basename($model));
    }

    /**
     * Mempermudah pencatatan log dengan JSON attributes lama & baru
     */
    protected function log(Model $model, string $action, string $description)
    {
        $oldValues = $action === 'updated' || $action === 'deleted' ? $model->getOriginal() : null;
        $newValues = $action === 'created' || $action === 'updated' ? $model->getAttributes() : null;
        
        if ($oldValues) {
            unset($oldValues['created_at'], $oldValues['updated_at']);
        }
        if ($newValues) {
            unset($newValues['created_at'], $newValues['updated_at']);
        }

        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'subject_type' => get_class($model),
            'subject_id' => $model->getKey(),
            'action' => $action,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'aktivitas' => $description, // backward compatibility untuk field lama
        ]);
    }
}
