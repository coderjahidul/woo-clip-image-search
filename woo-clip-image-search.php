<?php 
/*
Plugin Name: WooCommerce CLIP Image Search
Plugin URI: https://github.com/coderjahidul/woo-clip-image-search
Description: Search WooCommerce products by image using OpenAI CLIP embeddings.
Version: 1.0
Author: MD Jahidul islam sabuz
Author URI: https://github.com/coderjahidul/
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: woo-clip-image-search
Domain Path: /languages

*/

if(!defined('ABSPATH')) exit;

// include class-woo-clip-settings file
require_once plugin_dir_path(__FILE__) . '/includes/class-woo-clip-settings.php';

// Init Settings Page
new Woo_CLIP_Settings();

