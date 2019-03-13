<?php
/**
 * Listing controller.
 *
 * @package HivePress\Controllers
 */

namespace HivePress\Controllers;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Forms;
use HivePress\Blocks;
use HivePress\Emails;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing controller class.
 *
 * @class Listing
 */
class Listing extends Controller {

	/**
	 * Class constructor.
	 *
	 * @param array $args Controller arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'routes' => [
					[
						'path'      => '/listings',
						'rest'      => true,
						'endpoints' => [
							[
								'path'    => '/(?P<id>\d+)',
								'methods' => 'POST',
								'action'  => 'update_listing',
							],

							[
								'path'    => '/(?P<id>\d+)',
								'methods' => 'DELETE',
								'action'  => 'delete_listing',
							],
						],
					],

					[
						'rule'   => 'is_listings_page',
						'action' => 'render_listings_page',
					],

					[
						'rule'   => 'is_listing_page',
						'action' => 'render_listing_page',
					],
				],
			],
			$args
		);

		parent::__construct( $args );
	}

	/**
	 * Updates listing.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function update_listing( $request ) {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hp\rest_error( 401 );
		}

		// Get listing.
		$listing = Models\Listing::get( $request->get_param( 'id' ) );

		if ( is_null( $listing ) ) {
			return hp\rest_error( 404 );
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_others_posts' ) && ( get_current_user_id() !== $listing->get_user_id() || ! in_array( $listing->get_status(), [ 'auto-draft', 'draft', 'publish' ], true ) ) ) {
			return hp\rest_error( 403 );
		}

		// Validate form.
		$form = new Forms\Listing_Update();

		$form->set_values( $request->get_params() );

		if ( ! $form->validate() ) {
			return hp\rest_error( 400, $form->get_errors() );
		}

		// Update listing.
		$listing->fill( $form->get_values() );

		if ( ! $listing->save() ) {
			return hp\rest_error( 400, esc_html__( 'Error updating listing', 'hivepress' ) );
		}

		return new \WP_Rest_Response(
			[
				'data' => [
					'id' => $listing->get_id(),
				],
			],
			200
		);
	}

	/**
	 * Deletes listing.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function delete_listing( $request ) {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hp\rest_error( 401 );
		}

		// Get listing.
		$listing = Models\Listing::get( $request->get_param( 'id' ) );

		if ( is_null( $listing ) ) {
			return hp\rest_error( 404 );
		}

		// Check permissions.
		if ( ! current_user_can( 'delete_others_posts' ) && ( get_current_user_id() !== $listing->get_user_id() || ! in_array( $listing->get_status(), [ 'auto-draft', 'draft', 'publish' ], true ) ) ) {
			return hp\rest_error( 403 );
		}

		// Delete listing.
		if ( ! $listing->delete() ) {
			return hp\rest_error( 400, esc_html__( 'Error deleting listing', 'hivepress' ) );
		}

		return new \WP_Rest_Response( (object) [], 204 );
	}

	/**
	 * Checks listings page.
	 *
	 * @return bool
	 */
	public function is_listings_page() {

		// Get page ID.
		$page_id = absint( get_option( 'hp_page_listings' ) );

		return ( 0 !== $page_id && is_page( $page_id ) ) || is_post_type_archive( 'hp_listing' ) || is_tax( 'hp_listing_category' );
	}

	/**
	 * Renders listings page.
	 *
	 * @return string
	 */
	public function render_listings_page() {
		$output = ( new Blocks\Element( [ 'attributes' => [ 'file_path' => 'header' ] ] ) )->render();

		if ( ( is_page() && get_option( 'hp_page_listings_display_subcategories' ) ) || ( is_tax() && get_term_meta( get_queried_object_id(), 'hp_display_subcategories', true ) ) ) {
			$output .= ( new Blocks\Template( [ 'attributes' => [ 'template_name' => 'listing_categories_view_page' ] ] ) )->render();
		} else {
			$output .= ( new Blocks\Template( [ 'attributes' => [ 'template_name' => 'listings_view_page' ] ] ) )->render();
		}

		$output .= ( new Blocks\Element( [ 'attributes' => [ 'file_path' => 'footer' ] ] ) )->render();

		return $output;
	}

	/**
	 * Checks listing page.
	 *
	 * @return bool
	 */
	public function is_listing_page() {
		return is_singular( 'hp_listing' );
	}

	/**
	 * Renders listing page.
	 *
	 * @return string
	 */
	public function render_listing_page() {
		the_post();

		$output  = ( new Blocks\Element( [ 'attributes' => [ 'file_path' => 'header' ] ] ) )->render();
		$output .= ( new Blocks\Listing( [ 'attributes' => [ 'template_name' => 'listing_view_page' ] ] ) )->render();
		$output .= ( new Blocks\Element( [ 'attributes' => [ 'file_path' => 'footer' ] ] ) )->render();

		return $output;
	}
}
