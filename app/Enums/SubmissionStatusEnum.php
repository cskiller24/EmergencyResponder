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
        return match ($this) {
            static::DRAFT => 'Submission is on draft',
            static::SUBMITTED => 'Submission is submitted',
            static::APPROVED => 'Submission is approved',
            static::DECLINED => 'Submission is declined'
        };
    }

    public function titleCase(): string
    {
        return match ($this) {
            static::DRAFT => 'Draft',
            static::SUBMITTED => 'Submitted',
            static::APPROVED => 'Approved',
            static::DECLINED => 'Declined'
        };
    }

    public function isDraft(): bool
    {
        return match ($this) {
            static::DRAFT => true,
            default => false
        };
    }

    public function isSubmitted(): bool
    {
        return match ($this) {
            static::SUBMITTED => true,
            default => false
        };
    }

    public function isApproved(): bool
    {
        return match ($this) {
            static::APPROVED => true,
            default => false
        };
    }

    public function isDeclined(): bool
    {
        return match ($this) {
            static::DECLINED => true,
            default => false
        };
    }
}
