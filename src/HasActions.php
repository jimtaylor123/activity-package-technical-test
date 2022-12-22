<?php

declare(strict_types=1);

namespace Activity;

use App\Models\User;
use function Illuminate\Events\queueable;

trait HasActions
{
    public static function bootHasActions()
    {
        static::created(queueable(function ($model) {
            Action::create([
                'type' => 'create',
                'performerable_id' => request()->user()->id,
                'performerable_type' => User::class,
                'subjectable_id' => $model->id,
                'subjectable_type' => $model::class,
            ]);
        }));

        static::updated(queueable(function ($model) {
            Action::create([
                'type' => 'update',
                'performerable_id' => request()->user()->id,
                'performerable_type' => User::class,
                'subjectable_id' => $model->id,
                'subjectable_type' => $model::class,
            ]);
        }));

        static::deleted(queueable(function ($model) {
            Action::create([
                'type' => 'delete',
                'performerable_id' => request()->user()->id,
                'performerable_type' => User::class,
                'subjectable_id' => $model->id,
                'subjectable_type' => $model::class,
            ]);
        }));
    }

    public function actionsSubjected()
    {
        return $this->morphMany(Action::class, 'subjectable');
    }
}