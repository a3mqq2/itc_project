<?php

/**
 * Get the current area name from the URL
 * Extracts the area name from the URL path and converts hyphens to underscores
 *
 * @return string|null
 */
function get_area_name()
{
    if (auth()->check()) {
        return str_replace("-", "_", explode('/', request()->url())[3]);
    }
}

/**
 * Greet a user with a personalized message
 *
 * @param string $name
 * @return string
 */
if (!function_exists('greetUser')) {
    function greetUser($name)
    {
        return "Hello, {$name}!";
    }
}

/**
 * Format a Libyan phone number to the standard international format (218XXXXXXXXX)
 * Handles various input formats:
 * - 00218XXXXXXXXX -> 218XXXXXXXXX
 * - 218XXXXXXXXX -> 218XXXXXXXXX
 * - 0XXXXXXXXX -> 218XXXXXXXXX
 * - XXXXXXXXX -> 218XXXXXXXXX
 *
 * @param string $phone
 * @return string
 */
if (!function_exists('formatLibyanPhone')) {
    function formatLibyanPhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '00')) {
            $phone = substr($phone, 2);
        }
        if (str_starts_with($phone, '218')) {
            return $phone;
        }
        if (str_starts_with($phone, '0')) {
            return '218' . substr($phone, 1);
        }
        return '218' . $phone;
    }
}
