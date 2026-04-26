<?php

namespace App\Enums;

enum UserRole: string
{
    case Client = 'client';
    case Manager = 'manager';
}
