<?php

/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 1.2.0
 */

use Timber\Post;
use Timber\Timber;

$post = new Post();

$context = Timber::context();
$context['post'] = $post;

Timber::render(['page-' . $post->slug . '.twig', 'page.twig'], $context);
