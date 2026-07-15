<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Get the courses for the study program.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Course, $this>
     */
    public function courses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get the CPLs for the study program.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Cpl, $this>
     */
    public function cpls(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Cpl::class);
    }
}
