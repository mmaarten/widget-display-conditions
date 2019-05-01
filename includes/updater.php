<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * Updater
 */

final class WDC_Updater
{
	static private $instance = null;

	static public function get_instance()
	{
		if ( ! self::$instance ) 
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	private $tasks = array();
	private $page  = null;

	private function __construct()
	{
		
	}

	public function init()
	{
		add_action( 'admin_menu'   , array( &$this, 'add_page' ) );
		add_action( 'admin_init'   , array( &$this, 'check_version' ), 0 );
		add_action( 'admin_init'   , array( &$this, 'update' ) );
		add_action( 'admin_notices', array( &$this, 'admin_notices' ) );
	}

	public function check_version()
	{
		// Get saved version

		$version = get_option( 'wdc_version' );

		// Fallback for previous releases who may not have saved there version.

		if ( false === $version ) 
		{
			$version = apply_filters( 'wdc/db_version', false );
		}

		// Compare with current version

		if ( WDC_VERSION === $version ) return;

		// Save new version

		update_option( 'wdc_version', WDC_VERSION );

		// Store previous version for update purprose

		update_option( 'wdc_update' , $version );
	}

	public function add_task( $id, $version, $callback )
	{
		$this->tasks[ $id ] = compact( 'id', 'version', 'callback' );
	}

	public function get_tasks()
	{
		return $this->tasks;
	}

	public function get_applicable_tasks()
	{
		$curr_version = get_option( 'wdc_version' );
		$prev_version = get_option( 'wdc_update' );

		if ( false === $prev_version ) 
		{
			return array();
		}

		$tasks = array();

		foreach ( $this->tasks as $key => $task ) 
		{
			if ( version_compare( $task['version'], $prev_version, '>' )
			  && version_compare( $task['version'], $curr_version, '<=' ) ) 
			{
				$tasks[ $key ] = $task;
			}
		}

		return $tasks;
	}

	public function update()
	{
		if ( empty( $_POST[ WDC_NONCE_NAME ] ) )
		{
			return;
		}

		if ( ! wp_verify_nonce( $_POST[ WDC_NONCE_NAME ], 'update' ) ) 
		{
			return;
		}

		$tasks = $this->get_applicable_tasks();

		usort( $tasks, array( $this, 'sort_tasks' ) );

		foreach ( $tasks as $task ) 
		{
			call_user_func( $task['callback'] );
		}

		delete_option( 'wdc_update' );
	}

	public function add_page()
	{
		$this->page = add_submenu_page( null, __( 'Widget Display Conditions Updater', 'wdc' ), __( 'Updater', 'wdc' ), 'update_plugins', 'wdc-updater', array( &$this, 'render_page' ) );
	}

	public function render_page()
	{
		$tasks = $this->get_applicable_tasks();

		?>

		<div class="wrap">

			<h1><?php esc_html_e( 'Widget Display Conditions Updater', 'wdc' ); ?></h1>

			<?php if ( $tasks ) : ?>
			
			<form method="post">
					
				<?php wp_nonce_field( 'update', WDC_NONCE_NAME ); ?>

				<p><strong><?php esc_html_e( 'Database update is required.', 'wdc' ); ?></strong></p>
				<p><?php esc_html_e( 'Make sure to create a backup before updating.', 'wdc' ); ?></p>

				<?php submit_button( __( 'Update', 'wdc' ) ); ?>

			</form>

			<?php else : ?>
			<p><?php esc_html_e( 'Nothing to update.', 'wdc' ); ?></p>
			<?php endif; ?>

		</div>

		<?php
	}

	public function admin_notices()
	{
		$screen = get_current_screen();

		if ( $this->page == $screen->id ) 
		{
			return;
		}

		$tasks = $this->get_applicable_tasks();

		if ( ! $tasks ) 
		{
			return;
		}

		$plugin = get_plugin_data( WDC_PLUGIN_FILE );

		$message = sprintf( '<strong>%s</strong> %s <a href="%s">%s</a>', 
			esc_html__( $plugin['Name'] ), 
			esc_html__( 'Database update is required.', 'wdc' ),
			admin_url( '?page=wdc-updater' ),
			esc_html__( 'Go to update page', 'wdc' ) );

		printf( '<div class="notice notice-warning"><p>%s</p></div>', $message );
	}

	public function sort_tasks( $a, $b )
	{
		return version_compare( $a['version'], $b['version'] );
	}
}

WDC_Updater::get_instance()->init();

function wdc_add_update_task( $id, $version, $callback )
{
	$updater = WDC_Updater::get_instance();

	$updater->add_task( $id, $version, $callback );
}
