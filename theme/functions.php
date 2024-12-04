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
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );

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

		$dev_css_file = get_template_directory() . '/assets/dist/main.css';
		$dist_dir = get_template_directory() . '/assets/dist/';
		$dist_uri = get_template_directory_uri() . '/assets/dist/';

		if ( file_exists( $dev_css_file ) ) {
			// Development: Enqueue the non-hashed CSS file.
			wp_enqueue_style(
				'theme-styles',
				$dist_uri . 'main.css',
				array(),
				filemtime( $dev_css_file )
			);
		} else {
			// Production: Enqueue the hashed CSS file.
			$css_files = glob( $dist_dir . 'main.*.css' );

			if ( ! empty( $css_files ) ) {
				$hashed_css_file = basename( $css_files[0] ); // Use the first matched file.
				wp_enqueue_style(
					'theme-styles',
					$dist_uri . $hashed_css_file,
					array(),
					null
				);
			} else {
				// Fallback: Log error if no CSS file is found.
				if ( WP_DEBUG ) {
					error_log( 'No CSS file found in the production build.' );
				}
			}
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

	public function enqueue_block_editor_assets() {
		$dist_dir = get_template_directory() . '/assets/dist/';
		$dist_uri = get_template_directory_uri() . '/assets/dist/';

		// Check for hashed editor-style.css in production
		$css_files = glob( $dist_dir . 'editor-style.*.css' );

		if ( ! empty( $css_files ) ) {
			// Use the hashed file in production
			$hashed_css_file = basename( $css_files[0] );
			add_editor_style( $dist_uri . $hashed_css_file );
		} else {
			// Fallback to plain editor-style.css in development
			if ( file_exists( $dist_dir . 'editor-style.css' ) ) {
				add_editor_style( $dist_uri . 'editor-style.css' );
			} else {
				// Log an error if no CSS file is found
				if ( WP_DEBUG ) {
					error_log( 'No editor-style.css file found in the build directory.' );
				}
			}
		}
	}

	public function admin_head() {
		if (is_admin()) {
			$template_directory = get_template_directory_uri();
			echo <<<HTML
			<script type="module">
			import { Island } from "{$template_directory}/assets/javascript/is-land.js";
			import "{$template_directory}/assets/javascript/is-land-autoinit.js";
			</script>
			HTML;
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
