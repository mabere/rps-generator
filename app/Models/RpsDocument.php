<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RpsDocument extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'course_id',
        'user_id',
        'status',
        'generated_at',
    ];

    /**
     * Get the course for the RPS document.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Course, $this>
     */
    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user who owns/created the RPS document.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the weekly plans for the RPS document.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\RpsWeek, $this>
     */
    public function weeks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RpsWeek::class);
    }
}
