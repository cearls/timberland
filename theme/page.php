<?php

/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 0.2.0
 */

use Timber\Post;
use Timber\Timber;

$post = new Post();

$context = Timber::context();
$context['post'] = $post;

Timber::render(['page-' . $post->post_name . '.twig', 'page.twig'], $context);
