<?php 
/**
 * Updater
 */

namespace wdc;

class Updater
{
	protected $tasks     = array();
	protected $page_hook = null;

	public function __construct()
	{
		
	}

	public function init()
	{
		add_action( 'admin_init'               , array( &$this, 'save_version' ), 0 );
		add_action( 'update_option_wdc_version', array( &$this, 'version_change' ), 10, 2 );
		add_action( 'admin_init'               , array( &$this, 'update' ) );
		add_action( 'admin_menu'               , array( &$this, 'add_menu_page' ) );
		add_action( 'admin_notices'            , array( &$this, 'update_notice' ) );
	}

	/**
	 * Save version
	 */
	public function save_version()
	{
		update_option( 'wdc_version', get_version() );
	}

	/**
	 * Version change
	 */
	public function version_change( $old_version, $new_version )
	{
		update_option( 'wdc_update_from', $old_version );
	}

	/**
	 * Add task
	 *
	 * @param string   $id
	 * @param string   $version
	 * @param callable $callback
	 */
	public function add_task( $id, $version, $callback )
	{
		$this->tasks[ $id ] = compact( 'id', 'version', 'callback' );
	}

	/**
	 * Get tasks
	 *
	 * @return array
	 */
	public function get_tasks()
	{
		return $this->tasks;
	}

	/**
	 * Get task
	 *
	 * @param string $task_id
	 *
	 * @return mixed
	 */
	public function get_task( $task_id )
	{
		if ( isset( $this->tasks[ $task_id ] ) ) 
		{
			return $this->tasks[ $task_id ];
		}

		return null;
	}

	/**
	 * Get applicable tasks
	 *
	 * @return array
	 */
	public function get_applicable_tasks()
	{
		$from = get_option( 'wdc_update_from' );
		$to   = get_version();

		if ( ! has_widget_conditions() ) 
		{
			return array();
		}

		if ( false === $from ) 
		{
			return array();
		}
		
		if ( version_compare( $from, $to, '=' ) ) 
		{
			return array();
		}

		$tasks = array();

		foreach ( $this->tasks as $key => $task ) 
		{
			if ( version_compare( $task['version'], $from, '>' ) 
			  && version_compare( $task['version'], $to, '<=' ) ) 
			{
				$tasks[ $key ] = $task;
			}
		}

		uasort( $tasks, array( $this, 'sort_tasks' ) );

		return $tasks;
	}

	/**
	 * Sort tasks
	 *
	 * @param mixed $a
	 * @param mixed $b
	 *
	 * @return int
	 */
	public function sort_tasks( $a, $b )
	{
		return version_compare( $a['version'], $b['version'] );
	}

	/**
	 * Do tasks
	 *
	 * @return array
	 */
	protected function do_tasks()
	{
		$tasks = $this->get_applicable_tasks();

		$result = array();

		foreach ( $tasks as $task ) 
		{
			$result[ $task['id'] ] = call_user_func( $task['callback'] );
		}

		return $result;
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

		$result = $this->do_tasks();

		delete_option( 'wdc_update_from' );
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

			<pre><?php var_dump( $this->get_applicable_tasks() ); ?></pre>

			<?php if ( ! $this->get_applicable_tasks() ) : ?>
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
}

get_instance()->updater = new Updater();
get_instance()->updater->init();

function add_update_task( $id, $version, $callback )
{
	get_instance()->updater->add_task( $id, $version, $callback );
}
