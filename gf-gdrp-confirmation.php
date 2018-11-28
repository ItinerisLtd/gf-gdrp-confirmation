<?php
/**
 * Plugin Name:     GF GDRP Confirmation
 * Plugin URI:      https://www.itineris.co.uk/
 * Description:     Encrypt personal information in query string and pre-populate them to Gravity Forms fields
 * Version:         0.1.0
 * Author:          Itineris Limited
 * Author URI:      https://www.itineris.co.uk/
 * Text Domain:     gf-gdrp-confirmation
 */

declare(strict_types=1);

namespace Itineris\GFGDRPConfirmation;

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

add_action('plugins_loaded', function (): void
{
    $storage = StorageFactory::build();
    $queryString = new QueryString('gf_gdrp_', $storage);

    $confirmation = new Confirmation($queryString);
    $form = new Form($storage, $queryString);

    add_filter('gform_confirmation_ui_settings', [$confirmation, 'displayHelpMessage']);
    add_filter('gform_confirmation', [$confirmation, 'transformRedirectUrl']);
    add_filter('gform_field_value', [$form, 'populateFields'], 10, 3);
});
