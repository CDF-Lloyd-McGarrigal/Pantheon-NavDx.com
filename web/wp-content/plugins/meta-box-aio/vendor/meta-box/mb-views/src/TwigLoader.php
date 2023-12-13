<?php
namespace MBViews;

use MetaBox\Dependencies\Twig\Loader\LoaderInterface;
use MetaBox\Dependencies\Twig\Source;
use MetaBox\Dependencies\Twig\Error\LoaderError;

class TwigLoader implements LoaderInterface {
	/**
	 * Returns the source context for a given template logical name.
	 *
	 * @param string $name The template logical name
	 *
	 * @return Source
	 *
	 * @throws LoaderError When $name is not found
	 */
	public function getSourceContext( string $name ): Source {
		$view = is_numeric( $name ) ? get_post( $name ) : get_page_by_path( $name, OBJECT, 'mb-views' );

		if ( empty( $view ) ) {
			throw new LoaderError( sprintf( __( 'View "%s" is not defined.', 'mb-views' ), $name ) );
		}

		$source = $view->post_content;
		if ( $view->post_excerpt ) {
			$source .= "\n<style>\n{$view->post_excerpt}\n</style>";
		}
		if ( $view->post_content_filtered ) {
			$source .= "\n<script>\n{$view->post_content_filtered}\n</script>";
		}

		return new Source( $source, $name );
	}

	/**
	 * Gets the cache key to use for the cache for a given template name.
	 *
	 * @param string $name The name of the template to load
	 *
	 * @return string The cache key
	 *
	 * @throws LoaderError When $name is not found
	 */
	public function getCacheKey( string $name ): string {
		if ( ! $this->exists( $name ) ) {
			throw new LoaderError( sprintf( __( 'View "%s" is not defined.', 'mb-views' ), $name ) );
		}
		return $name;
	}

	/**
	 * Returns true if the template is still fresh.
	 *
	 * @param string    $name The template name
	 * @param timestamp $time The last modification time of the cached template
	 *
	 * @return bool    true if the template is fresh, false otherwise
	 *
	 * @throws LoaderError When $name is not found
	 */
	public function isFresh( string $name, int $time ): bool {
		$view = is_numeric( $name ) ? get_post( $name ) : get_page_by_path( $name, OBJECT, 'mb-views' );

		if ( empty( $view ) ) {
			throw new LoaderError( sprintf( __( 'View "%s" is not defined.', 'mb-views' ), $name ) );
		}

		return strtotime( $view->post_modified_date ) <= $time;
	}

	/**
	 * Check if we have the source code of a template, given its name.
	 *
	 * @param string $name The name of the template to check if we can load
	 *
	 * @return bool    If the template source code is handled by this loader or not
	 */
	public function exists( string $name ) {
		$view = is_numeric( $name ) ? get_post( $name ) : get_page_by_path( $name, OBJECT, 'mb-views' );
		return ! empty( $view );
	}
}