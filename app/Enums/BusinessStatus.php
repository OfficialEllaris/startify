<?php

namespace App\Enums;

enum BusinessStatus: string
{
    case Submitted = 'submitted';
    case UnderReview = 'under_review';
    case InProgress = 'in_progress';
    case Filed = 'filed';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
