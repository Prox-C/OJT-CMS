<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class HTESkill extends Pivot
{
    protected $table = 'hte_skill';

    protected $fillable = [
        'hte_id',
        'skill_id'
    ];
}