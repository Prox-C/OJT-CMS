<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternDocument extends Model
{
    protected $fillable = [
        'intern_id',
        'type',
        'file_path',
        'original_name'
    ];

    public static function typeLabels(): array
    {
        return [
            'requirements_checklist' => 'OJT Requirements Checklist (EVSU-IEA-F-042)',
            'certificate_of_registration' => 'Certificate of Registration',
            'report_of_grades' => 'Report of Grades',
            'application_resume' => 'Application Letter & Resume',
            'medical_certificate' => 'Medical Certificate',
            'parent_consent' => 'Parent Consent (EVSU-IEA-041)',
            'insurance_certificate' => 'Insurance Certificate',
            'pre_deployment_certification' => 'Pre-Deployment Certification',
            'ojt_fee_reciept' => 'OJT Fee Reciept',
        ];
    }

    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    // Accessor for download-friendly filename
    public function getDownloadFilenameAttribute(): string
    {
        $internId = $this->intern->student_id;
        $type = str_replace('_', '-', $this->type);
        return "{$internId}-{$type}.pdf";
    }
}