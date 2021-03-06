<?php
/**
 * Toggle block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Toggle block class.
 *
 * @class Toggle
 */
class Toggle extends Block {

	/**
	 * Toggle view.
	 *
	 * @var string
	 */
	protected $view = 'link';

	/**
	 * Toggle icon.
	 *
	 * @var string
	 */
	protected $icon;

	/**
	 * Toggle URL.
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * Toggle captions.
	 *
	 * @var array
	 */
	protected $captions = [];

	/**
	 * Toggle attributes.
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * Active flag.
	 *
	 * @var bool
	 */
	protected $active = false;

	/**
	 * Bootstraps block properties.
	 */
	protected function boot() {
		$attributes = [];

		// Set attributes.
		if ( 'link' === $this->view ) {
			$attributes['class'] = [ 'hp-link' ];
		}

		if ( is_user_logged_in() ) {
			$attributes['href'] = '#';

			$attributes['data-component'] = 'toggle';
			$attributes['data-url']       = esc_url( $this->url );

			if ( $this->active ) {
				$attributes['data-caption'] = hp\get_first_array_value( $this->captions );
				$attributes['data-state']   = 'active';

				if ( 'icon' === $this->view ) {
					$attributes['title'] = hp\get_last_array_value( $this->captions );
				}
			} else {
				$attributes['data-caption'] = hp\get_last_array_value( $this->captions );

				if ( 'icon' === $this->view ) {
					$attributes['title'] = hp\get_first_array_value( $this->captions );
				}
			}
		} else {
			$attributes['href'] = '#user_login_modal';
		}

		$this->attributes = hp\merge_arrays( $this->attributes, $attributes );

		parent::boot();
	}

	/**
	 * Renders block HTML.
	 *
	 * @return string
	 */
	public function render() {
		$output = '<a ' . hp\html_attributes( $this->attributes ) . '>';

		// Render icon.
		if ( $this->icon ) {
			$output .= '<i class="hp-icon fas fa-' . esc_attr( $this->icon ) . '"></i>';
		}

		// Render captions.
		if ( 'icon' !== $this->view ) {
			$output .= '<span>';

			if ( $this->active ) {
				$output .= esc_html( hp\get_last_array_value( $this->captions ) );
			} else {
				$output .= esc_html( hp\get_first_array_value( $this->captions ) );
			}

			$output .= '</span>';
		}

		$output .= '</a>';

		return $output;
	}
}
