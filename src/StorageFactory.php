<?php
declare(strict_types=1);

namespace Itineris\GFGDRPConfirmation;

class StorageFactory
{
    public static function build(): Storage
    {
        return new Storage(
            static::getOrGenerate(QueryString::ID_QUERY, 12),
            static::getOrGenerate(QueryString::PASSWORD_QUERY, 64)
        );
    }

    public static function getOrGenerate($key, $length): string
    {
        $value = null;
        if (isset($_GET[$key])) {
            $value = sanitize_text_field(wp_unslash($_GET[$key])); // WPCS: CSRF, input var ok.
        }

        return empty($value)
            ? (string) wp_generate_password($length, false)
            : $value;
    }
}
