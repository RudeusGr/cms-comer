<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class);
    }

    public function device(): BelongsTo {
        return $this->belongsTo(Device::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    
}
