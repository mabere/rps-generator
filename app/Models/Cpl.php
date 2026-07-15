<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cpl extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'study_program_id',
        'code',
        'description',
        'type',
    ];

    /**
     * Get the study program that owns the CPL.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\StudyProgram, $this>
     */
    public function studyProgram(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    /**
     * Get the courses that target this CPL.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Course, $this>
     */
    public function courses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }
}
