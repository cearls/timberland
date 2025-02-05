<?php
/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 2.1.0
 */

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

Timber\Timber::init();
Timber::$dirname    = array( 'views', 'blocks' );
Timber::$autoescape = false;

class Timberland extends Timber\Site {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'block_categories_all', array( $this, 'block_categories_all' ) );
		add_action( 'acf/init', array( $this, 'acf_register_blocks' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_assets' ) );

		parent::__construct();
	}

	public function add_to_context( $context ) {
		$context['site'] = $this;
		$context['menu'] = Timber::get_menu();

		// Require block functions files
		foreach ( glob( __DIR__ . '/blocks/*/functions.php' ) as $file ) {
			require_once $file;
		}

		return $context;
	}

	public function add_to_twig( $twig ) {
		return $twig;
	}

	public function theme_supports() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		add_theme_support( 'menus' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'editor-styles' );
	}

	public function enqueue_assets() {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' );
		wp_dequeue_script( 'jquery' );
		wp_dequeue_style( 'global-styles' );

		$vite_env = 'production';

		if ( file_exists( get_template_directory() . '/../config.json' ) ) {
			$config   = json_decode( file_get_contents( get_template_directory() . '/../config.json' ), true );
			$vite_env = $config['vite']['environment'] ?? 'production';
		}

		$dist_uri  = get_template_directory_uri() . '/assets/dist';
		$dist_path = get_template_directory() . '/assets/dist';
		$manifest  = null;

		if ( file_exists( $dist_path . '/.vite/manifest.json' ) ) {
			$manifest = json_decode( file_get_contents( $dist_path . '/.vite/manifest.json' ), true );
		}

		if ( is_array( $manifest ) ) {
			if ( $vite_env === 'production' || is_admin() ) {
				$js_file = 'theme/assets/main.js';
				wp_enqueue_style( 'main', $dist_uri . '/' . $manifest[ $js_file ]['css'][0] );
				$strategy = is_admin() ? 'async' : 'defer';
				$in_footer = is_admin() ? false : true;
				wp_enqueue_script(
					'main',
					$dist_uri . '/' . $manifest[ $js_file ]['file'],
					array(),
					'',
					array(
						'strategy'  => $strategy,
						'in_footer' => $in_footer,
					)
				);

				// wp_enqueue_style('prefix-editor-font', '//fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap');
				$editor_css_file = 'theme/assets/styles/editor-style.css';
				add_editor_style( $dist_uri . '/' . $manifest[ $editor_css_file ]['file'] );
			}
		}

		if ( $vite_env === 'development' ) {
			function vite_head_module_hook() {
				echo '<script type="module" crossorigin src="http://localhost:3000/@vite/client"></script>';
				echo '<script type="module" crossorigin src="http://localhost:3000/theme/assets/main.js"></script>';
			}
			add_action( 'wp_head', 'vite_head_module_hook' );
		}
	}

	public function block_categories_all( $categories ) {
		return array_merge(
			array(
				array(
					'slug'  => 'custom',
					'title' => __( 'Custom' ),
				),
			),
			$categories
		);
	}

	public function acf_register_blocks() {
		$blocks = array();

		foreach ( new DirectoryIterator( __DIR__ . '/blocks' ) as $dir ) {
			if ( $dir->isDot() ) {
				continue;
			}

			if ( file_exists( $dir->getPathname() . '/block.json' ) ) {
				$blocks[] = $dir->getPathname();
			}
		}

		asort( $blocks );

		foreach ( $blocks as $block ) {
			register_block_type( $block );
		}
	}
}

new Timberland();

function acf_block_render_callback( $block, $content ) {
	$context           = Timber::context();
	$context['post']   = Timber::get_post();
	$context['block']  = $block;
	$context['fields'] = get_fields();
    $block_name        = explode( '/', $block['name'] )[1];
    $template          = 'blocks/'. $block_name . '/index.twig';

	Timber::render( $template, $context );
}

// Remove ACF block wrapper div
function acf_should_wrap_innerblocks( $wrap, $name ) {
	return false;
}

add_filter( 'acf/blocks/wrap_frontend_innerblocks', 'acf_should_wrap_innerblocks', 10, 2 );
