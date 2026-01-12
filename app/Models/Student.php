<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class);
    }
}
