<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cpmk extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'course_id',
        'cpl_id',
        'statement',
        'indicators',
    ];

    /**
     * Get the course that owns the CPMK.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Course, $this>
     */
    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the CPL that this CPMK maps to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Cpl, $this>
     */
    public function cpl(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cpl::class);
    }
}
