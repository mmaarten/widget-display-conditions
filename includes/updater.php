<?php 
/**
 * Updater
 */

namespace wdc;

$wdc_update_tasks = array();

function save_version()
{
	update_option( 'wdc_version', WDC_VERSION );
}

add_action( 'init', __NAMESPACE__ . '\save_version' );

function version_change( $old_version, $new_version )
{
	update_option( 'wdc_update', $old_version );
}

add_action( 'update_option_wdc_version', __NAMESPACE__ . '\version_change', 10, 2 );

function add_update_task( $id, $version, $callback )
{
	$GLOBALS['wdc_update_tasks'][ $id ] = compact( 'id', 'version', 'callback' );
}

function get_update_tasks( $applicable = true )
{
	$all_tasks = $GLOBALS['wdc_update_tasks'];

	if ( ! $applicable ) 
	{
		return $all_tasks;
	}

	$prev_version = get_option( 'wdc_update' );
	$curr_version = WDC_VERSION;

	if ( false === $prev_version ) 
	{
		return array();
	}

	$tasks = array();

	foreach ( $all_tasks as $key => $task ) 
	{
		if ( version_compare( $task['version'], $prev_version, '>' )
		  && version_compare( $task['version'], $curr_version, '<=' ) )
		{
			$tasks[ $key ] = $task;
		}
	}

	uasort( $tasks, __NAMESPACE__ . '\sort_update_tasks' );

	return $tasks;
}

function sort_update_tasks( $a, $b )
{
	return version_compare( $a['version'], $b['version'] );
}

function update()
{
	if ( empty( $_POST[ WDC_NONCE_NAME ] ) ) 
	{
		return;
	}

	if ( ! wp_verify_nonce( $_POST[ WDC_NONCE_NAME ], 'update' ) ) 
	{
		return;
	}

	$tasks = get_update_tasks();

	$result = array();

	foreach ( $tasks as $key => $task ) 
	{
		$result[ $key ] = call_user_func( $task['callback'] );
	}

	delete_option( 'wdc_update', WDC_VERSION );
}

add_action( 'admin_init', __NAMESPACE__ . '\update' );

function updater_add_menu_page()
{
	add_submenu_page( null, __( 'Widget Display Conditions Updater', 'wdc' ), __( 'Updater', 'wdc' ), 'update_plugins', 'wdc-updater', __NAMESPACE__ . '\updater_render_page' );
}

add_action( 'admin_menu', __NAMESPACE__ . '\updater_add_menu_page' );

function updater_render_page()
{
	$tasks = get_update_tasks();

	?>

	<div class="wrap">

		<h1><?php esc_html_e( 'Widget Display Conditions Updater', 'wdc' ); ?></h1>

		<?php if ( ! $tasks ) : ?>
		<p><?php esc_html_e( 'No updates available.' ); ?></p>
		<?php else : ?>

		<form method="post">
			
			<?php wp_nonce_field( 'update', WDC_NONCE_NAME ); ?>

			<p><strong><?php _e( 'A database update is required.', 'wdc' ); ?></strong></p>
			<p><?php _e( 'Make sure to make a backup before updating.', 'wdc' ); ?></p>

			<?php submit_button( __( 'Update', 'wdc' ) ); ?>

		</form>

		<?php endif; ?>

	</div><!-- .wrap -->

	<?php
}

function updater_notices()
{
	// Get tasks

	$tasks = get_update_tasks();

	// Stop when no tasks

	if ( ! $tasks ) return;

	// Output notice

	$plugin = get_plugin_data( WDC_FILE );

	$message = sprintf( '<strong>%s</strong>: %s <a href="%s">%s</a>.',
		esc_html( $plugin['Name'] ),
		esc_html__( 'Database needs to be updated.', 'wdc' ),
		admin_url( '?page=wdc-updater', 'wdc' ),
		esc_html__( 'Go to update page', 'wdc' ) );

	admin_notice( $message, 'warning' );
}

add_action( 'admin_notices', __NAMESPACE__ . '\updater_notices' );
