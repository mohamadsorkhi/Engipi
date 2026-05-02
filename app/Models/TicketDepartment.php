<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDepartment extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'department_id');
    }
}
