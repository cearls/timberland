<?php

/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 1.0.0
 */

use Timber\Timber;

$context = Timber::context();

Timber::render('sidebar.twig', $context);
