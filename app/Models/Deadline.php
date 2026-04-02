<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Deadline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'deadline'
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function isOverdue(): bool
    {
        if (!$this->deadline) return false;
        return Carbon::now()->startOfDay()->gt($this->deadline);
    }

    public function daysRemaining(): ?int
    {
        if (!$this->deadline) return null;
        
        $today = Carbon::now()->startOfDay();
        $deadline = Carbon::parse($this->deadline)->startOfDay();
        
        if ($deadline->lt($today)) {
            return 0;
        }
        
        return $today->diffInDays($deadline);
    }

    public function getFormattedDeadlineAttribute(): string
    {
        if (!$this->deadline) return 'Not set';
        return Carbon::parse($this->deadline)->format('F j, Y');
    }

    public function getStatusBadgeAttribute(): string
    {
        if (!$this->deadline) return 'secondary';
        
        if ($this->isOverdue()) {
            return 'danger';
        }
        
        $daysRemaining = $this->daysRemaining();
        
        if ($daysRemaining <= 3) {
            return 'warning';
        }
        
        return 'success';
    }

    public function getStatusTextAttribute(): string
    {
        if (!$this->deadline) return 'Not set';
        
        if ($this->isOverdue()) {
            return 'Overdue';
        }
        
        $daysRemaining = $this->daysRemaining();
        
        if ($daysRemaining == 0) {
            return 'Today';
        }
        
        if ($daysRemaining == 1) {
            return 'Tomorrow';
        }
        
        return $daysRemaining . ' days remaining';
    }
}