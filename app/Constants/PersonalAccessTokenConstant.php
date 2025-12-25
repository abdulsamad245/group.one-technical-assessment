<?php

namespace App\Constants;

/**
 * Personal Access Token table column constants.
 */
class PersonalAccessTokenConstant
{
    // Table name
    public const TABLE = 'personal_access_tokens';

    // Primary key
    public const ID = 'id';

    // Morph name (used with uuidMorphs)
    public const TOKENABLE = 'tokenable';

    // Columns
    public const TOKENABLE_TYPE = 'tokenable_type';
    public const TOKENABLE_ID = 'tokenable_id';
    public const NAME = 'name';
    public const TOKEN = 'token';
    public const ABILITIES = 'abilities';
    public const LAST_USED_AT = 'last_used_at';
    public const EXPIRES_AT = 'expires_at';

    // Timestamps
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
}

