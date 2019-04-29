<?php 
/**
 * Updater
 */

namespace wdc;

/**
 * Updater
 */
final class Updater
{
	static private $instance = null;

	static public function get_instance()
	{
		if ( ! Self::$instance ) 
		{
			Self::$instance = new Self();
		}

		return Self::$instance;
	}

	protected $tasks = array();
	protected $page  = null;

	private function __construct()
	{
		
	}

	public function init()
	{
		add_action( 'admin_menu'               , array( &$this, 'add_page' ) );
		add_action( 'admin_init'               , array( &$this, 'save_version' ) );
		add_action( 'admin_init'               , array( &$this, 'update' ), 15 );
		add_action( 'admin_notices'            , array( &$this, 'admin_notices' ) );
		add_action( 'update_option_wdc_version', array( &$this, 'version_change' ), 10, 2 );
	}

	public function save_version()
	{
		update_option( 'wdc_version', WDC_VERSION );
	}

	public function version_change( $old_version, $new_version )
	{
		update_option( 'wdc_update', $old_version );
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
		$prev_version = get_option( 'wdc_update' );
		$curr_version = get_option( 'wdc_version' );

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

		uasort( $tasks, array( $this, 'sort_tasks' ) );

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

		$result = array();

		foreach ( $tasks as $key => $task ) 
		{
			$result[ $key ] = call_user_func( $task['callback'] );
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

	public function admin_notices()
	{
		// Stop when current page is our page

		$screen = get_current_screen();

		if ( $this->page == $screen->id ) 
		{
			return;
		}

		// Get tasks

		$tasks = $this->get_applicable_tasks();

		// Stop when no tasks

		if ( ! $tasks ) return;

		// Output notice

		$plugin = get_plugin_data( WDC_FILE );

		$message = sprintf( '<strong>%s</strong>: %s <a href="%s">%s</a>.',
			esc_html( $plugin['Name'] ),
			esc_html__( 'Database needs to be updated.', 'wdc' ),
			admin_url( '?page=wdc-updater', 'wdc' ),
			esc_html__( 'Go to update page', 'wdc' ) );

		printf( '<div class="notice notice-warning"><p>%s</p></div>', $message );
	}

	public function sort_tasks( $a, $b )
	{
		return version_compare( $a['version'], $b['version'] );
	}
}

Updater::get_instance()->init();
