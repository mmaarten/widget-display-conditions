<?php 

namespace wdc;

function app_init()
{
	// Add operators

	add_operator( '==', __( 'Is equal to', 'wdc' ), array
	(
		'order' => 10,
	));

	add_operator( '!=', __( 'Is not equal to', 'wdc' ), array
	(
		'order' => 20,
	));

	// Add param categories

	add_param_category( 'post', __( 'Post', 'wdc' ), array
	(
		'order' => 10,
	));

	add_param_category( 'page', __( 'Page', 'wdc' ), array
	(
		'order' => 20,
	));

	add_param_category( 'attachment', __( 'Media', 'wdc' ), array
	(
		'order' => 30,
	));

	// Add params

	add_param( 'post', __( 'Post', 'wdc' ), array
	(
		'category'  => 'post',
		'operators' => array( '==', '!=' ),
		'order'     => 1000,
	));

	add_param( 'page', __( 'Page', 'wdc' ), array
	(
		'category'  => 'page',
		'operators' => array( '==', '!=' ),
		'order'     => 1000,
	));

	add_param( 'attachment', __( 'Attachment', 'wdc' ), array
	(
		'category'  => 'attachment',
		'operators' => array( '==', '!=' ),
		'order'     => 1000,
	));
}

add_action( 'init', __NAMESPACE__ . '\app_init' );

function value_field_items( $items, $param_id )
{
	switch ( $param_id ) 
	{
		case 'post' :
			
			$post_types = get_post_types( array( 'public' => true ), 'names' );

			if ( isset( $post_types['page'] ) )       unset( $post_types['page'] );
			if ( isset( $post_types['attachment'] ) ) unset( $post_types['attachment'] );

			return get_post_field_items( $post_types );
		
		case 'page' :
			
			return get_post_field_items( 'page' );

		case 'attachment' :
			
			return get_post_field_items( 'attachment' );
	}

	return $items;
}

add_filter( 'wdc/value_field_items/param=post'      , 'wdc\value_field_items', 10, 2 );
add_filter( 'wdc/value_field_items/param=page'      , 'wdc\value_field_items', 10, 2 );
add_filter( 'wdc/value_field_items/param=attachment', 'wdc\value_field_items', 10, 2 );
