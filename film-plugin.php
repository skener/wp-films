<?php

/**
 * Plugin name: Films Plugin
 * Plugin URI:        https://github.com/skener/wp-films
 * Description:       Custom Film post.
 * Version:           1.0.0
 * Author:            Andriy Tserkovnyk <skenerster@gmail.com>
 * Author URI:        https://skener.github.io/cv
 * Text Domain:       skener
 *
 * @package WordPress.
 */

// check WP env is loaded
if (!defined('ABSPATH') || !function_exists('add_action')) {
    exit('No WP here');
}

if (version_compare(get_bloginfo('version'), '5.0', '<')) {
    wp_die(__("You must update WordPress to use this plugin.", 'films'));
}

require_once __DIR__ . '/vendor/autoload.php';

use Films\Film;

if (!class_exists('Films\Film')) {
    echo 'Something with Autoloading!';
    return;
}

new Film();
