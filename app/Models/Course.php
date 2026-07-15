<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'study_program_id',
        'code',
        'name',
        'sks',
        'semester',
        'description',
    ];

    /**
     * Get the study program that owns the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\StudyProgram, $this>
     */
    public function studyProgram(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    /**
     * Get the CPLs that this course maps to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Cpl, $this>
     */
    public function cpls(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Cpl::class);
    }

    /**
     * Get the CPMKs for this course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Cpmk, $this>
     */
    public function cpmks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Cpmk::class);
    }

    /**
     * Get the RPS documents generated for this course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\RpsDocument, $this>
     */
    public function rpsDocuments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RpsDocument::class);
    }
}
