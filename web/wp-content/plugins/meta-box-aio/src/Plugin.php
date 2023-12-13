<?php
namespace MBAIO;

class Plugin {
	public function __construct() {
		require MBAIO_DIR . '/vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php';
		add_action( 'tgmpa_register', [$this, 'require_plugins'] );
	}

	public function require_plugins() {
		$plugins = [
			[
				'name'     => 'Meta Box',
				'slug'     => 'meta-box',
				'required' => true,
			]
		];
		$config = [
			'id'           => 'mb-aio',
			'menu'         => 'mb-aio-install-plugins',
			'parent_slug'  => 'plugins.php',
			'capability'   => 'manage_options',
			'strings' => [
				'notice_can_install_required'     => _n_noop(
					// translators: 1: plugin name(s).
					'Meta Box AIO requires %1$s to work. Please install and activate it.',
					'Meta Box AIO requires %1$s to work. Please install and activate it.',
					'meta-box-aio'
				),
			],
		];
		tgmpa( $plugins, $config );
	}
}