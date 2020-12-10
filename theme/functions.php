<?php

/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 0.1.0
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Timber\Menu;
use Timber\Post;
use Timber\Site;
use Timber\Timber;
use Timber\PostQuery;

$timber = new Timber();

Timber::$dirname = array('../views', '../components');

class Timberland extends Site
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('after_setup_theme', [$this, 'theme_supports']);
        add_filter('timber/context', [$this, 'add_to_context']);
        add_filter('timber/twig', [$this, 'add_to_twig']);
        add_filter('use_block_editor_for_post_type', [$this, 'use_block_editor_for_post_type'], 10, 2);
        add_action('init', [$this, 'register_custom_post_types']);
        add_action('init', [$this, 'register_taxonomies']);

        parent::__construct();
    }

    public function add_to_context($context)
    {
        $context['site'] = $this;
        $context['menu'] = new Menu();

        $args = array(
            'post_type' => 'page',
            'posts_per_page' => -1,
        );

        $context['pages'] = new PostQuery($args);

        $context['options'] = get_fields('option');

        return $context;
    }

    public function add_to_twig($twig)
    {
        return $twig;
    }

    public function theme_supports()
    {
        add_theme_support('automatic-feed-links');
        add_theme_support(
            'html5',
            [
                'comment-form',
                'comment-list',
                'gallery',
                'caption'
            ]
        );
        add_theme_support('menus');
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');

        /** Removing the Website field from WordPress comments is a proven way to reduce spam */
        add_filter('comment_form_default_fields', 'remove_website_field');
        function remove_website_field($fields)
        {
            if (isset($fields['url'])) {
                unset($fields['url']);
            }
            return $fields;
        }

        /** Limit comment depth to two. If you need more, you will need to adjust the Tailwind styling */
        add_filter('thread_comments_depth_max', function ($max) {
            return 2;
        });
    }

    public function enqueue_scripts()
    {
        $version = '0.1.0';

        if (WP_DEBUG === true) {
            $version = time();
        }

        // wp_dequeue_style('wp-block-library');
        // wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_script('jquery');

        wp_enqueue_style('style', '/wp-content/themes/' . basename(dirname(__DIR__, 1)) . '/assets/build/app.css', array(), $version);
        wp_enqueue_script('app', '/wp-content/themes/' . basename(dirname(__DIR__, 1)) . '/assets/build/app.js', array(), $version, true);
    }

    public function use_block_editor_for_post_type($is_enabled, $post_type)
    {
        if ($post_type === 'page') return false;
        return $is_enabled;
    }

    public function register_custom_post_types()
    {
        // Example
        // $labels = [
        //     "name" => "Example",
        //     "singular_name" => "Example",
        // ];

        // $args = [
        //     "label" => "Example",
        //     "labels" => $labels,
        //     "description" => "",
        //     "public" => true,
        //     "publicly_queryable" => true,
        //     "show_ui" => true,
        //     "show_in_rest" => true,
        //     "rest_base" => "",
        //     "rest_controller_class" => "WP_REST_Posts_Controller",
        //     "has_archive" => false,
        //     "show_in_menu" => true,
        //     "show_in_nav_menus" => true,
        //     "delete_with_user" => false,
        //     "exclude_from_search" => false,
        //     "capability_type" => "post",
        //     "map_meta_cap" => true,
        //     "hierarchical" => false,
        //     "rewrite" => ["slug" => "example", "with_front" => true],
        //     "query_var" => true,
        //     "menu_icon" => "dashicons-groups",
        //     "supports" => ["title"],
        // ];

        // register_post_type("example", $args);
    }

    public function register_taxonomies()
    {
        // Example
        // $labels = [
        //     "name" => "Example",
        //     "singular_name" => "Example",
        // ];

        // $args = [
        //     "label" => "Example",
        //     "labels" => $labels,
        //     "public" => true,
        //     "publicly_queryable" => true,
        //     "hierarchical" => false,
        //     "show_ui" => true,
        //     "show_in_menu" => true,
        //     "show_in_nav_menus" => true,
        //     "query_var" => true,
        //     "rewrite" => ['slug' => 'example', 'with_front' => true,],
        //     "show_admin_column" => false,
        //     "show_in_rest" => true,
        //     "rest_base" => "example",
        //     "rest_controller_class" => "WP_REST_Terms_Controller",
        //     "show_in_quick_edit" => false,
        // ];

        // register_taxonomy("example", ["posttype"], $args);
    }
}

new Timberland();
