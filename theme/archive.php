<?php

/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 1.1.0
 */

use Timber\PostQuery;
use Timber\Timber;

$context = Timber::context();

switch ($wp_query) {
    case is_day():
        $context['title'] = 'Archive: ' . get_the_date('D M Y');
        break;

    case is_month():
        $context['title'] = 'Archive: ' . get_the_date('M Y');
        break;

    case is_year():
        $context['title'] = 'Archive: ' . get_the_date('Y');
        break;

    case is_tag():
        $context['title'] = single_tag_title('', false);
        break;

    case is_category():
        $context['title'] = single_cat_title('', false);
        break;

    case is_post_type_archive():
        $context['title'] = post_type_archive_title('', false);
        break;

    default:
        $context['title'] = 'Archive';
        break;
}

$context['posts'] = new PostQuery();

Timber::render('archive.twig', $context);
