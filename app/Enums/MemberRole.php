<?php

namespace App\Enums;

enum MemberRole: string
{
    case Organizer = 'organizer';
    case Member = 'member';
    case Manager = 'manager';
    case RegisteredAgent = 'registered_agent';
}
