<?php 
/**
 * Updater
 */

namespace wdc;

final class Updater
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

	protected $tasks     = array();
	protected $page_hook = null;

	private function __construct()
	{
		
	}

	public function init()
	{
		add_action( 'admin_init'   , array( &$this, 'update' ) );
		add_action( 'admin_menu'   , array( &$this, 'add_menu_page' ) );
		add_action( 'admin_notices', array( &$this, 'update_notice' ) );
	}

	public function add_task( $id, $version, $callback )
	{
		$task = array
		(
			'id'       => $id,
			'version'  => $version,
			'callback' => $callback,
		);

		$this->tasks[ $task['id'] ] = $task;
	}

	public function get_tasks()
	{
		return $this->tasks;
	}

	public function get_applicable_tasks()
	{
		$from = get_option( 'wdc_version' );
		$to   = WDC_VERSION;

		if ( version_compare( $from, $to, '=' ) ) 
		{
			return array();
		}

		$tasks = array();

		foreach ( $this->tasks as $key => $task ) 
		{
			if ( version_compare( $task['version'], $from , '<' ) ) 
			{
				continue;
			}

			if ( version_compare( $task['version'], $to , '>' ) ) 
			{
				continue;
			}

			$tasks[ $key ] = $task;
		}

		uasort( $tasks, 'wdc\sort_version' );

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

		$results = array();

		foreach ( $tasks as $key => $task ) 
		{
			$results[ $key ] = call_user_func( $task['callback'] );
		}

		update_option( 'wdc_version', WDC_VERSION );
	}

	public function update_notice()
	{
		$screen = get_current_screen();

		if ( $this->page_hook == $screen->id ) 
		{
			return;
		}

		if ( ! $this->get_applicable_tasks() ) 
		{
			return;
		}

		$message = sprintf( '<strong>%s</strong>: %s <a href="%s">%s</a>', 
			esc_html__( 'Widget Display Conditions', 'wdc' ),
			esc_html__( 'Database update is required.', 'wdc' ),
		 	admin_url( 'admin.php?page=wdc-updater' ),
		 	esc_html__( 'Go to update page.', 'wdc' ) );

		admin_notice( $message, 'warning' );
	}

	public function add_menu_page()
	{
		$this->page_hook = add_submenu_page( null, __( 'Widget Display Conditions Updater', 'wdc' ), __( 'Updater', 'wdc' ), 'update_plugins', 'wdc-updater', array( &$this, 'render_page' ) );
	}

	public function render_page()
	{
		?>

		<div class="wrap">

			<h1><?php esc_html_e( 'Widget Display Conditions Updater', 'wdc' ); ?></h1>

			<?php if ( ! $this->get_applicable_tasks() ) : ?>
			<p><?php esc_html_e( 'No updates available.' ); ?></p>
			<?php else : ?>

			<form method="post">
				
				<?php wp_nonce_field( 'update', WDC_NONCE_NAME ); ?>

				<pre><?php print_r( $this->get_applicable_tasks() ); ?></pre>

				<p><strong><?php _e( 'A database update is required.', 'wdc' ); ?></strong></p>
				<p><?php _e( 'Make sure to make a backup before updating.', 'wdc' ); ?></p>

				<?php submit_button( __( 'Update', 'wdc' ) ); ?>

			</form>

			<?php endif; ?>

		</div><!-- .wrap -->

		<?php
	}
}

Updater::get_instance()->init();

function add_update_task( $id, $version, $callback )
{
	$updater = Updater::get_instance();

	$updater->add_task( $id, $version, $callback );
}
