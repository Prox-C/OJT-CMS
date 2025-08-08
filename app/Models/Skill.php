<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $primaryKey = 'skill_id';
    
    protected $fillable = [
        'name',
        'dept_id'
    ];

    /**
     * Get the department that owns the skill
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }

    /**
     * Get the students that have this skill
     */
    public function students()
    {
        return $this->belongsToMany(Intern::class, 'student_skill', 'skills_id', 'intern_id');
    }

    public function htes()
    {
        return $this->belongsToMany(HTE::class, 'hte_skill')
                    ->using(HTESkill::class)
                    ->withTimestamps();
    }
}