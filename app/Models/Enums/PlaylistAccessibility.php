<?php

namespace App\Models\Enums;

enum PlaylistAccessibility : string
{
    case PUBLIC = 'PUBLIC';
    case PRIVATE = 'PRIVATE';
}
