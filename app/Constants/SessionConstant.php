<?php

namespace App\Constants;

/**
 * Session table column constants.
 */
class SessionConstant
{
    // Table name
    public const TABLE = 'sessions';

    // Primary key
    public const ID = 'id';

    // Columns
    public const USER_ID = 'user_id';
    public const IP_ADDRESS = 'ip_address';
    public const USER_AGENT = 'user_agent';
    public const PAYLOAD = 'payload';
    public const LAST_ACTIVITY = 'last_activity';
}
