<?php
/**
 * Renderer for map fields: map, osm. These fields are not cloneable.
 */
namespace MBViews\Fields;

use RWMB_Field;

class MapRenderer extends BaseRenderer {
	public static function get_single_value( $value ) {
		// Groups send location, normal fields send array of map info.
		if ( ! is_array( $value ) ) {
			list( $latitude, $longitude, $zoom ) = explode( ',', $value . ',,' );
			$value = compact( 'latitude', 'longitude', 'zoom' );
		}
		return array_merge( $value, [
			'rendered' => RWMB_Field::call( self::$field, 'render_map', implode( ',', $value ), [] ),
		] );
	}
}