<?php
// app/Models/CoordinatorEvaluation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoordinatorEvaluation extends Model
{
    use HasFactory;
    
    protected $table = 'coordinator_evaluations';
    
    protected $fillable = [
        'intern_id',
        'coordinator_id',
        'hte_id',
        'communication',
        'responsiveness',
        'support',
        'guidance',
        'fairness',
        'professionalism',
        'timeliness',
        'clarity',
        'average_rating',
        'comments',
        'suggestions',
        'status',
        'evaluated_at'
    ];
    
    protected $casts = [
        'evaluated_at' => 'datetime',
        'communication' => 'decimal:2',
        'responsiveness' => 'decimal:2',
        'support' => 'decimal:2',
        'guidance' => 'decimal:2',
        'fairness' => 'decimal:2',
        'professionalism' => 'decimal:2',
        'timeliness' => 'decimal:2',
        'clarity' => 'decimal:2',
        'average_rating' => 'decimal:2',
    ];
    
    // Relationships
    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class);
    }
    
    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Coordinator::class);
    }
    
    public function hte(): BelongsTo
    {
        return $this->belongsTo(Hte::class);
    }
    
    // Helper methods
    public static function getCriteriaLabels(): array
    {
        return [
            'communication' => 'Communication Skills',
            'responsiveness' => 'Responsiveness to Concerns',
            'support' => 'Support Provided',
            'guidance' => 'Guidance & Mentorship',
            'fairness' => 'Fairness',
            'professionalism' => 'Professionalism',
            'timeliness' => 'Timeliness',
            'clarity' => 'Clarity of Instructions',
        ];
    }
    
    public static function getRatingLabels(): array
    {
        return [
            1 => 'Very Poor',
            2 => 'Poor',
            3 => 'Average',
            4 => 'Good',
            5 => 'Excellent',
        ];
    }
}