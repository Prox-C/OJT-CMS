<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class InternsHte extends Model
{
    use HasFactory;
    protected $table = 'interns_hte';
    protected $fillable = [
        'intern_id',
        'hte_id',
        'status',
        'endorsed_at',
        'deployed_at',
    ];
    protected $casts = [
        'endorsed_at' => 'datetime',
        'deployed_at' => 'datetime',
    ];
    // Relationships
    public function intern()
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }
    public function hte()
    {
        return $this->belongsTo(Hte::class, 'hte_id');
    }
}