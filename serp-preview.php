<?php
/*
Plugin Name: Show SERP Preview
Description: Displays how your page appears in search engine results.
Version: 1.0.0
Author: Moki-Moki Ios
Author URI: http://mokimoki.net/
Text Domain: serp-preview
License: GPL3
*/

/*
Copyright (C) 2017 Moki-Moki Ios http://mokimoki.net/

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * SERP Preview
 * Displays how your page appears in search engine results.
 *
 * @version 1.0.0
 */

if (!defined('ABSPATH')) return;

require_once(__DIR__.'/serp-preview-ui.php');

add_action('init', array(SERPPreview::get_instance(), 'initialize'));
add_action('admin_notices', array(SERPPreview::get_instance(), 'plugin_activation_notice'));
register_activation_hook(__FILE__, array(SERPPreview::get_instance(), 'setup_plugin_on_activation')); 

/**
 * Main class of the plugin.
 */
class SERPPreview {
	
	const PLUGIN_NAME = "Show SERP Preview";
	const VERSION = '1.0.0';
	const TEXT_DOMAIN = 'serp-preview';
	
	private static $instance;
	private static $ui;
	
	private function __construct() {}
		
	public static function get_instance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
			self::$ui = new SERPPreviewUi();
		}
		return self::$instance;
	}
	
	public function initialize() {
		load_plugin_textdomain(self::TEXT_DOMAIN, FALSE, basename(dirname( __FILE__ )) . '/languages');
		
		add_action('admin_enqueue_scripts', array($this, 'add_admin_style'));
		add_action('admin_menu', array($this, 'post_page_init'));
	}
	
	public function setup_plugin_on_activation() {		
		set_transient('serp_preview_activation_notice', TRUE, 5);
		add_action('admin_notices', array($this, 'plugin_activation_notice'));
	}	
	
	public function post_page_init(){
		add_action('add_meta_boxes', array(self::$ui,'add_post_metaboxes'));
	}

	public function add_admin_style() {
		wp_register_style('serp_preview_admin_style', plugin_dir_url(__FILE__) . 'serp-preview.css');
		wp_enqueue_style('serp_preview_admin_style');
	}
	
	public function plugin_activation_notice() {
		if (get_transient('serp_preview_activation_notice')) {
			echo '<div class="notice updated"><p><strong>'.__('SERP Preview is activated. No further actions required &ndash; the plugin is now up and running!', self::TEXT_DOMAIN).'</strong></p></div>';	
		}		
	}
}
