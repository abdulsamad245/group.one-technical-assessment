<?php

namespace App\Constants;

/**
 * License Event table column constants.
 */
class LicenseEventConstant
{
    // Table name
    public const TABLE = 'license_events';

    // Primary key
    public const ID = 'id';

    // Foreign keys
    public const LICENSE_ID = 'license_id';

    // Columns
    public const EVENT_TYPE = 'event_type';
    public const DESCRIPTION = 'description';
    public const EVENT_DATA = 'event_data';
    public const IP_ADDRESS = 'ip_address';
    public const USER_AGENT = 'user_agent';
    public const METADATA = 'metadata';

    // Timestamps
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
}
