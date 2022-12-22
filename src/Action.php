<?php

declare(strict_types=1);

namespace Activity;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $guarded = [];

    public function performerable()
    {
        return $this->morphTo();
    }

    public function subjectable()
    {
        return $this->morphTo();
    }

    public function description(): string
    {
        $performer = $this->performerable;
        $date = $this->created_at->toDateTimeString();
        return __('activity-package-technical-test::actions.description', [
            "performerName" => $performer->name,
            "performerId" => $performer->id,
            "actionType" => $this->type,
            "subjectableType" => $this->subjectable_type,
            "subjectableId" => $this->subjectable_id,
            "date"=> $date,
        ]);
    }
}
