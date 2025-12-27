<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General Messages
    |--------------------------------------------------------------------------
    */
    'unexpected_error' => 'An unexpected error occurred. Please try again later.',
    'success' => 'Operation completed successfully.',
    'not_found' => 'Resource not found.',
    'unauthorized' => 'Unauthorized access.',
    'forbidden' => 'Access forbidden.',
    'validation_error' => 'Validation error.',

    /*
    |--------------------------------------------------------------------------
    | Brand Messages
    |--------------------------------------------------------------------------
    */
    'brand_created' => 'Brand created successfully.',
    'brand_updated' => 'Brand updated successfully.',
    'brand_deleted' => 'Brand deleted successfully.',
    'brand_found' => 'Brand retrieved successfully.',
    'brands_found' => 'Brands retrieved successfully.',
    'brand_not_found' => 'Brand not found.',

    /*
    |--------------------------------------------------------------------------
    | License Messages
    |--------------------------------------------------------------------------
    */
    'license_created' => 'License created successfully.',
    'license_updated' => 'License updated successfully.',
    'license_deleted' => 'License deleted successfully.',
    'license_found' => 'License retrieved successfully.',
    'licenses_found' => 'Licenses retrieved successfully.',
    'license_not_found' => 'License not found.',
    'license_suspended' => 'License suspended successfully.',
    'license_reactivated' => 'License reactivated successfully.',
    'license_renewed' => 'License renewed successfully.',
    'license_resumed' => 'License resumed successfully.',
    'license_canceled' => 'License canceled successfully.',
    'license_cannot_activate' => 'This license cannot be activated.',
    'license_expired' => 'This license has expired.',
    'license_not_active' => 'This license is not active.',
    'license_already_exists_for_customer' => 'A license already exists for this customer and product.',

    /*
    |--------------------------------------------------------------------------
    | License Key Messages
    |--------------------------------------------------------------------------
    */
    'license_key_created' => 'License key created successfully.',
    'license_key_generated' => 'License key generated successfully.',
    'license_key_found' => 'License key retrieved successfully.',
    'license_keys_found' => 'License keys retrieved successfully.',
    'license_key_not_found' => 'License key not found.',
    'license_key_invalid' => 'The provided license key is invalid.',
    'license_key_not_valid' => 'The license key is not valid.',
    'license_key_cancelled' => 'License key has been cancelled.',

    /*
    |--------------------------------------------------------------------------
    | Activation Messages
    |--------------------------------------------------------------------------
    */
    'license_activated' => 'License activated successfully.',
    'license_deactivated' => 'License deactivated successfully.',
    'activation_found' => 'Activation retrieved successfully.',
    'activations_found' => 'Activations retrieved successfully.',
    'activation_not_found' => 'Activation not found.',
    'activation_status_checked' => 'Activation status checked successfully.',
    'activation_already_exists' => 'Device is already activated.',
    'max_activations_reached' => 'Maximum activations reached for ":instance_type". Limit: :max.',
    'instance_type_not_configured' => 'Instance type ":instance_type" is not configured for this license.',
    'license_not_found_for_product' => 'No license was found for the specified product.',

    /*
    |--------------------------------------------------------------------------
    | Customer Messages
    |--------------------------------------------------------------------------
    */
    'customer_licenses_found' => 'Customer licenses retrieved successfully.',
    'customer_not_found' => 'Customer not found.',

    /*
    |--------------------------------------------------------------------------
    | Auth Messages
    |--------------------------------------------------------------------------
    */
    'user_registered' => 'User registered successfully.',
    'user_logged_in' => 'User logged in successfully.',
    'user_logged_out' => 'User logged out successfully.',
    'user_retrieved' => 'User retrieved successfully.',
    'invalid_credentials' => 'Invalid email or password.',

    /*
    |--------------------------------------------------------------------------
    | API Key Messages
    |--------------------------------------------------------------------------
    */
    'api_key_created' => 'API key created successfully.',
    'api_key_rotated' => 'API key rotated successfully.',
    'api_key_cancelled' => 'API key cancelled successfully.',
    'api_keys_retrieved' => 'API keys retrieved successfully.',
    'api_key_not_found' => 'API key not found.',

    /*
    |--------------------------------------------------------------------------
    | Validation Messages
    |--------------------------------------------------------------------------
    */
    'invalid_input' => 'Invalid input provided.',
    'required_field' => 'This field is required.',
    'invalid_email' => 'Invalid email address.',
    'invalid_date' => 'Invalid date format.',
    'email_required' => 'Email address is required.',
    'email_invalid' => 'Please provide a valid email address.',
    'license_key_required' => 'License key is required.',
    'device_identifier_required' => 'Device identifier is required.',
    'license_id_required' => 'License ID is required.',
    'activation_id_required' => 'Activation ID is required.',
    'activation_id_invalid' => 'Activation ID must be a valid UUID.',
];
