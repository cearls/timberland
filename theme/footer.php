<?php

/**
 * Third party plugins that hijack the theme will call wp_footer() to get the footer template.
 * We use this to end our output buffer (started in header.php) and render into the view/page-plugin.twig template.
 *
 * If you're not using a plugin that requries this behavior (ones that do include Events Calendar Pro and
 * WooCommerce) you can delete this file and header.php
 *
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 1.1.0
 */

use Timber\Timber;

$context = Timber::context();

$context['content'] = ob_get_contents();

ob_end_clean();

Timber::render('page-plugin.twig', $context);
