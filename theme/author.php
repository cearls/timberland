<?php

/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 1.1.0
 */

use Timber\PostQuery;
use Timber\Timber;
use Timber\User;

$context = Timber::context();

$context['posts'] = new PostQuery();

if (isset($wp_query->query_vars['author'])) {
    $author = new User($wp_query->query_vars['author']);

    $context['author'] = $author;
    $context['title'] = 'Author Archives: ' . $author->name();
}

Timber::render('author.twig', $context);
