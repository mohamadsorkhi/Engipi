<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SkillSuggestion extends Model
{
    use HasFactory, HasUuids;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const TYPE_PROCESSING = 'software';
    public const TYPE_FIELD = 'field';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'skill_name',
        'skill_type',
        'normalized_name',
        'pending_name',
        'subdomain_id',
        'description',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public static function normalizeName(string $name): string
    {
        $name = str_replace(['ي', 'ى', 'ك', "\u{200C}", "\u{200D}"], ['ی', 'ی', 'ک', ' ', ' '], $name);
        $name = preg_replace('/\s+/u', ' ', trim($name)) ?? trim($name);

        return Str::lower($name);
    }

    public static function types(): array
    {
        return [self::TYPE_PROCESSING, self::TYPE_FIELD];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subdomain()
    {
        return $this->belongsTo(Subdomain::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
