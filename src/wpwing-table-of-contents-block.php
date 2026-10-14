<?php

/**
 * Plugin Name:        Table of Contents (TOC) Block - Fast & SEO Friendly
 * Plugin URI:         https://wpwing.com/
 * Description:        Automated, ultra-fast Table of Contents block built to boost SEO and readability with zero frontend JavaScript.
 * Version:            1.12.0
 * Requires at least:  5.8
 * Tested up to:       7.1
 * Requires PHP:       7.1
 * Author:             WPWing
 * Author URI:         https://wpwing.com/
 * License:            GPL-3.0-or-later
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:        wpwing-table-of-contents-block
 * Domain Path:        /languages
 */

define( 'WPWING_TOC_FILE', __FILE__ );
define( 'WPWING_TOC_DIR', __DIR__ );

require_once __DIR__ . '/includes/block-registration.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/importers/class-importer-luckywp.php';
require_once __DIR__ . '/includes/admin-menu.php';
require_once __DIR__ . '/includes/render.php';
require_once __DIR__ . '/includes/auto-insert.php';
