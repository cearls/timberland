<?php

/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 0.2.0
 */

use Timber\Timber;

$context = Timber::context();

Timber::render('sidebar.twig', $context);
