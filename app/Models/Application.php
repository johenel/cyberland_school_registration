<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    const STATUS_PENDING = 1;
    const STATUS_PROCCESSING = 2;
    const STATUS_ON_HOLD = 3;
    const STATUS_REJECTED = 4;
    const STATUS_ACCEPTED = 5;

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
