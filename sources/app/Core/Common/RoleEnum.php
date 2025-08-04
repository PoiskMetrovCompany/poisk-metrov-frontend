<?php
namespace App\Core\Common;
enum RoleEnum: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Client = 'client';
}
