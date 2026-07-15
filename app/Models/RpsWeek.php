<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RpsWeek extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'rps_document_id',
        'week_number',
        'sub_cpmk',
        'learning_methods',
        'assessment_criteria',
        'weight',
        'resources',
    ];

    /**
     * Get the RPS document that owns this weekly plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\RpsDocument, $this>
     */
    public function rpsDocument(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RpsDocument::class);
    }
}
