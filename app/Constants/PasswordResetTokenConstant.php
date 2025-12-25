<?php

namespace App\Constants;

/**
 * Password Reset Token table column constants.
 */
class PasswordResetTokenConstant
{
    // Table name
    public const TABLE = 'password_reset_tokens';

    // Primary key
    public const EMAIL = 'email';

    // Columns
    public const TOKEN = 'token';
    public const CREATED_AT = 'created_at';
}

