<?php

declare(strict_types=1);

namespace Activity;

use Activity\Action;

trait PerformsActions
{
    public function actionsPerformed()
    {
        return $this->morphMany(Action::class, 'performerable');
    }
}