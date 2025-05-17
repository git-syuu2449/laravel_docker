<?php

// app/Enums/Role.php
namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case User = 'general';
    case Guest = 'guest';

    public function label(): string
    {
        return match ($this) {
            Role::Admin  => '管理者',
            Role::User => '一般',
            Role::Guest => 'ゲスト',
        };
    }
}