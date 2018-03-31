<?php
/**
 * Plugin Name:     _Optimg
 * Plugin URI:      https://github.com/miya0001/_optimg
 * Description:     Optimize uploaded images.
 * Author:          Takayuki Miyauchi
 * Author URI:      https://miya.io/
 * Text Domain:     _optimg
 * Domain Path:     /languages
 * Version:         nightly
 *
 * @package         _optimg
 */

require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

add_action( 'init', '_optimg_activate_updater' );

function _optimg_activate_updater() {
	$plugin_slug = plugin_basename( __FILE__ );
	$gh_user = 'miya0001';
	$gh_repo = '_optimg';

	new Miya\WP\GH_Auto_Updater( $plugin_slug, $gh_user, $gh_repo );
}

add_filter( 'wp_image_editors', function( $editors ) {
	if ( ! class_exists( '_WP_Image_Editor_GD' ) ) {
		class _WP_Image_Editor_GD extends WP_Image_Editor_GD {
			protected function _save( $image, $filename = null, $mime_type = null ) {
				$saved = parent::_save( $image, $filename, $mime_type );
				_optimg::optimize( $saved );

				return $saved;
			}
		};
	}

	if ( ! class_exists( '_WP_Image_Editor_Imagick' ) ) {
		class _WP_Image_Editor_Imagick extends WP_Image_Editor_Imagick {
			protected function _save( $image, $filename = null, $mime_type = null ) {
				$saved = parent::_save( $image, $filename, $mime_type );
				_optimg::optimize( $saved );

				return $saved;
			}
		};
	}

	return array(
		'_WP_Image_Editor_GD',
		'_WP_Image_Editor_Imagick',
	);
}, 10 );


class _optimg
{
	const jpegoptim_path = '/usr/bin/jpegoptim';
	const pngquant_path = '/usr/bin/pngquant';

	public static function optimize( $saved )
	{
		if ( ! empty( $saved["mime-type"] ) && 'image/jpeg' == $saved["mime-type"] ) {
			self::jpegoptim( $saved['path'] );
		} elseif ( ! empty( $saved["mime-type"] ) && 'image/png' == $saved["mime-type"] ) {
			self::pngquant( $saved['path'] );
		}
	}

	public static function jpegoptim( $path, $quality = 60 ) {
		if ( ! is_executable( self::get_jpegoptim() ) ) {
			trigger_error( '`jpegoptim` is not executable, please install it.' );
			return;
		}
		if ( is_file( $path ) ) {
			$cmd = sprintf(
				'%s -m%d --strip-all %s 2>&1',
				self::get_jpegoptim(),
				intval( $quality ),
				escapeshellarg( $path )
			);
			$result = exec( $cmd, $output, $status );
			if ( $status ) {
				trigger_error( $result );
			}
		}
	}

	public static function pngquant( $path, $quality = 1 ) {
		if ( ! is_executable( self::get_pngquant() ) ) {
			trigger_error( '`pngquant` is not executable, please install it.' );
			return;
		}
		if ( is_file( $path ) ) {
			$cmd = sprintf(
				'%s --ext .png --force --speed %d %s 2>&1',
				self::get_pngquant(),
				intval( $quality ),
				escapeshellarg( $path )
			);
			$result = exec( $cmd, $output, $status );
			if ( $status ) {
				trigger_error( $result );
			}
		}
	}

	private static function get_jpegoptim()
	{
		if ( defined( 'JPEGOPTIM_PATH' ) && JPEGOPTIM_PATH ) {
			return JPEGOPTIM_PATH;
		} else {
			return self::jpegoptim_path;
		}
	}

	private static function get_pngquant()
	{
		if ( defined( 'PNGQUANT_PATH' ) && PNGQUANT_PATH ) {
			return PNGQUANT_PATH;
		} else {
			return self::pngquant_path;
		}
	}
}