<?php

namespace App\Enums;

enum DocumentType: string
{
    case ArticlesOfOrganization = 'articles_of_organization';
    case EinLetter = 'ein_letter';
    case OperatingAgreement = 'operating_agreement';
    case Other = 'other';
}
