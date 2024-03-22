<?php
/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 2.0.0
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

Timber\Timber::init();
Timber::$dirname = [ 'views', 'blocks' ];
Timber::$autoescape = false;

class Timberland extends Timber\Site
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('after_setup_theme', [$this, 'theme_supports']);
        add_filter('timber/context', [$this, 'add_to_context']);
        add_filter('timber/twig', [$this, 'add_to_twig']);
        add_action('block_categories_all', [$this, 'block_categories_all']);
        add_action('acf/init', [$this, 'acf_register_blocks']);
        //add_filter('allowed_block_types', [$this, 'allowed_block_types']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_assets']);

        parent::__construct();
    }

    public function add_to_context($context)
    {
        $context['site'] = $this;
        $context['menu'] = Timber::get_menu();

        // Require block functions files
        foreach (glob(dirname(__FILE__) . "/blocks/*/functions.php") as $file) {
            require_once $file;
        }

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
        add_theme_support('editor-styles');
    }

    public function enqueue_assets()
    {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-block-style');
        wp_dequeue_script('jquery');

        $env = 'production';
        if (file_exists(get_template_directory() . '/../config.json')) {
            $config = json_decode(file_get_contents(get_template_directory() . '/../config.json'), true);
            $env = $config['environment'] ?? 'production';
        }

        if ($env === 'local') {
            function vite_head_module_hook() {
                echo '<script type="module" crossorigin src="http://localhost:3000/theme/assets/main.js"></script>';
            }
            add_action('wp_head', 'vite_head_module_hook');
        }
        else {
            $dist_uri = get_template_directory_uri() . '/assets/dist';
            $dist_path = get_template_directory() . '/assets/dist';
            $manifest = json_decode(file_get_contents( $dist_path . '/manifest.json'), true);

            if (is_array($manifest)) {
                $css_file = 'theme/assets/main.css';
                $editor_css_file = 'theme/assets/styles/editor-style.css';

                if (is_admin()) {
                    //wp_enqueue_style('prefix-editor-font', '//fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap');
                    add_editor_style( $dist_uri . '/' . $manifest[$editor_css_file]['file'] );
                }
                else {
                    wp_enqueue_style( 'main', $dist_uri . '/' . $manifest[$css_file]['file'] );
                }

                $js_file = 'theme/assets/main.js';
                wp_enqueue_script( 'main', $dist_uri . '/' . $manifest[$js_file]['file'], [], '', array('strategy' => 'defer', 'in_footer' => true) );
            }
        }
    }

    public function block_categories_all($categories)
    {
        return array_merge([['slug' => 'custom', 'title' => __('Custom')]], $categories);
    }

    public function acf_register_blocks()
    {
        $blocks = [];

        foreach (new DirectoryIterator(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'blocks') as $dir) {
            if ($dir->isDot()) continue;

            if (file_exists($dir->getPathname() . DIRECTORY_SEPARATOR . 'block.json')) {
                $blocks[] = $dir->getPathname();
            }
        }

        asort($blocks);

        foreach ($blocks as $block) {
            register_block_type($block);
        }
    }


    public function allowed_block_types()
    {
        $allowed_blocks = [
            'core/columns'
        ];

        foreach (new DirectoryIterator(dirname(__FILE__) . '/blocks') as $dir) {
            $allowed_blocks[] = 'acf/' . $dir;
        }

        return $allowed_blocks;
    }
}

new Timberland();

function acf_block_render_callback($block, $content) {
    $context = Timber::context();
    $context['post'] = Timber::get_post();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $template = $block['path'] . '/index.twig';

    Timber::render($template, $context);
}

// Remove ACF block wrapper div
function acf_should_wrap_innerblocks($wrap, $name) {
    // if ( $name == 'acf/test-block' ) {
    //     return true;
    // }
    return false;
}

add_filter('acf/blocks/wrap_frontend_innerblocks', 'acf_should_wrap_innerblocks', 10, 2);
