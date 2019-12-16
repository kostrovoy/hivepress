<?php
/**
 * Site footer block template.
 *
 * @template site_footer_block
 * @description Site footer block.
 * @package HivePress\Templates
 */

namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Site footer block template class.
 *
 * @class Site_Footer_Block
 */
class Site_Footer_Block extends Template {

	/**
	 * Template blocks.
	 *
	 * @var array
	 */
	protected static $blocks = [];

	/**
	 * Class initializer.
	 *
	 * @param array $args Template arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_trees(
			[
				'blocks' => [
					'user_login_modal'            => [
						'type'    => 'modal',
						'caption' => esc_html__( 'Sign In', 'hivepress' ),

						'blocks'  => [
							'user_login_form' => [
								'type'  => 'user_login_form',
								'order' => 10,
							],
						],
					],

					'user_register_modal'         => [
						'type'    => 'modal',
						'caption' => esc_html__( 'Register', 'hivepress' ),

						'blocks'  => [
							'user_register_form' => [
								'type'       => 'form',
								'form'       => 'user_register',
								'order'      => 10,

								'attributes' => [
									'class' => [ 'hp-form--narrow' ],
								],

								'footer'     => [
									'form_actions' => [
										'type'       => 'container',
										'order'      => 10,

										'attributes' => [
											'class' => [ 'hp-form__actions' ],
										],

										'blocks'     => [
											'user_login_link' => [
												'type'     => 'element',
												'filepath' => 'user/register/user-login-link',
												'order'    => 10,
											],
										],
									],
								],
							],
						],
					],

					'user_password_request_modal' => [
						'type'    => 'modal',
						'caption' => esc_html__( 'Reset Password', 'hivepress' ),

						'blocks'  => [
							'user_password_request_form' => [
								'type'       => 'form',
								'form'       => 'user_password_request',
								'order'      => 10,

								'attributes' => [
									'class' => [ 'hp-form--narrow' ],
								],
							],
						],
					],
				],
			],
			$args,
			'blocks'
		);

		parent::init( $args );
	}
}
