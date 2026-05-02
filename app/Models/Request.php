<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Request extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'project_id',
        'user_id',
        'message',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the project that the request belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user (worker) who sent the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get created_at in Jalali format.
     *
     * @param  string  $value
     * @return \Morilog\Jalali\Jalalian
     */
    public function getCreatedAtAttribute($value)
    {
        return Jalalian::fromDateTime($value);
    }

    /**
     * Get updated_at in Jalali format.
     *
     * @param  string  $value
     * @return \Morilog\Jalali\Jalalian
     */
    public function getUpdatedAtAttribute($value)
    {
        return Jalalian::fromDateTime($value);
    }
}
