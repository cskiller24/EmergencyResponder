<?php

declare(strict_types=1);

namespace App\Enums;

enum SubmissionStatusEnum: int
{
    case DRAFT = 1;
    case SUBMITTED = 2;
    case APPROVED = 3;
    case DECLINED = 4;

    public function getMessage(): string
    {
        return match($this) {
            static::DRAFT => 'Submission is on draft',
            static::SUBMITTED => 'Submission is submitted',
            static::APPROVED => 'Submission is approved',
            static::DECLINED => 'Submission is declined'
        };
    }

    public function titleCase(): string
    {
        return match($this) {
            static::DRAFT => 'Draft',
            static::SUBMITTED => 'Submitted',
            static::APPROVED => 'Approved',
            static::DECLINED => 'Declined'
        };
    }
}
