<?php
/**
 * Attachment component.
 *
 * @package HivePress\Components
 */

namespace HivePress\Components;

use HivePress\Helpers as hp;
use HivePress\Models;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Attachment component class.
 *
 * @class Attachment
 */
final class Attachment extends Component {

	/**
	 * Class constructor.
	 *
	 * @param array $args Component arguments.
	 */
	public function __construct( $args = [] ) {

		// Delete attachment.
		add_action( 'hivepress/v1/models/attachment/delete', [ $this, 'delete_attachment' ] );

		// Delete attachments.
		add_action( 'hivepress/v1/models/user/delete', [ $this, 'delete_attachments' ], 10, 2 );
		add_action( 'hivepress/v1/models/post/delete', [ $this, 'delete_attachments' ], 10, 2 );
		add_action( 'hivepress/v1/models/term/delete', [ $this, 'delete_attachments' ], 10, 2 );
		add_action( 'hivepress/v1/models/comment/delete', [ $this, 'delete_attachments' ], 10, 2 );

		parent::__construct( $args );
	}

	/**
	 * Deletes attachment.
	 *
	 * @param int $attachment_id Attachment ID.
	 */
	public function delete_attachment( $attachment_id ) {

		// Get attachment.
		$attachment = Models\Attachment::query()->get_by_id( $attachment_id );

		// Get parent object.
		$parent = $attachment->get_parent();

		if ( empty( $parent ) ) {
			return;
		}

		// Get parent field.
		$parent_field = hp\get_array_value( $parent->_get_fields(), $attachment->get_parent_field() );

		if ( empty( $parent_field ) || $parent_field::get_meta( 'name' ) !== 'attachment_upload' ) {
			return;
		}

		if ( ! $parent_field->is_multiple() ) {

			// Update parent object.
			$parent->fill(
				[
					$parent_field->get_name() => null,
				]
			)->save();
		}
	}

	/**
	 * Deletes attachments.
	 *
	 * @param int    $parent_id Parent ID.
	 * @param string $parent_alias Parent alias.
	 */
	public function delete_attachments( $parent_id, $parent_alias ) {

		// Get parent type.
		$parent_type = reset( ( array_slice( explode( '/', current_action() ), -2, 1 ) ) );

		// Get parent model.
		$parent_model = hivepress()->model->get_model_name( $parent_type, $parent_alias );

		if ( empty( $parent_model ) ) {
			return;
		}

		// Delete attachments.
		$attachments = Models\Attachment::query()->filter(
			[
				'parent'       => $parent_id,
				'parent_model' => $parent_model,
			]
		)->delete();
	}
}
