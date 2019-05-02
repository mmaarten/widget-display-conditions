<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * UI
 */

class WDC_UI
{
	/**
	 * Init
	 */
	public static function init()
	{
		add_action( 'in_widget_form'       , array( __CLASS__, 'in_widget_form' ), 999 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'scripts' ) );
		add_action( 'admin_footer'         , array( __CLASS__, 'template_scripts' ) );

		add_action( 'wp_ajax_wdc_ui_get_condition_field_items', array( __CLASS__, 'get_condition_field_items_ajax' ) );
		add_action( 'wp_ajax_wdc_ui_preload', array( __CLASS__, 'preload' ) );
		add_action( 'wp_ajax_wdc_ui_update' , array( __CLASS__, 'update' ) );
	}

	/**
	 * Get condition field items
	 *
	 * @param string $condition_id
	 *
	 * @return mixed
	 */
	public static function get_condition_field_items( $condition_id )
	{
		$condition = wdc_get_condition( $condition_id );

		if ( ! $condition ) return null;

		$items = array
		(
			'operator' => wdc_get_condition_operator_field_items( $condition->id ),
			'value'    => wdc_get_condition_value_field_items( $condition->id ),
		);

		$items['operator'] = wdc_prepare_field_items_json( $items['operator'] );
		$items['value']    = wdc_prepare_field_items_json( $items['value'] );

		return $items;
	}

	/**
	 * In widget form
	 *
	 * @param WP_Widget $widget
	 */
	public static function in_widget_form( $widget )
	{
		if ( 'widgets.php' != $GLOBALS['pagenow'] ) return;
		
		// Output button to open UI
		$button = sprintf( '<button class="button wdc-open-ui" type="button" data-widget="%s" data-noncename="%s" data-nonce="%s">%s</button>',
			esc_attr( $widget->id ), esc_attr( WDC_NONCE_NAME ), esc_attr( wp_create_nonce( 'ui' ) ), esc_html__( 'Display conditions', 'wdc' ) );

		printf( '<p class="wdc-open-ui-wrap">%s<span class="spinner"></span></p>', $button );
	}

	/**
	 * Get condition field items ajax
	 */
	public static function get_condition_field_items_ajax()
	{
		if ( ! self::doing_ajax() ) return;

		$items = self::get_condition_field_items( $_POST['param'] );

		wp_send_json( $items );
	}

	/**
	 * Preload
	 */
	public static function preload()
	{
		if ( ! self::doing_ajax() ) return;

		// Get widget conditions

		$conditions = wdc_get_widget_conditions( $_POST['widget'] );

		// Get conditions field items

		$fields = array();

		if ( isset( $conditions ) ) 
		{
			foreach ( $conditions as $group ) 
			{
				foreach ( $group as $condition ) 
				{
					$condition_id = $condition['param'];

					// Check if already added
					if ( ! isset( $fields[ $condition_id ] ) ) 
					{
						$fields[ $condition_id ] = self::get_condition_field_items( $condition_id );
					}
				}
			}
		}

		// Response

		wp_send_json( array
		(
			'conditions' => $conditions,
			'fieldItems' => $fields,
		));
	}

	/**
	 * Update
	 */
	public static function update()
	{
		if ( ! self::doing_ajax() ) return;

		$conditions = isset( $_POST['conditions'] ) ? $_POST['conditions'] : array();

		$result = wdc_set_widget_conditions( $_POST['widget'], $conditions );

		wp_send_json( $result );
	}

	/**
	 * Scripts
	 */
	public static function scripts()
	{
		if ( 'widgets.php' != $GLOBALS['pagenow'] ) return;

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'dashicons' );

		// Featherlight
		wp_enqueue_script( 'featherlight', plugins_url( "assets/js/featherlight$min.js", WDC_PLUGIN_FILE ), array( 'jquery' ), '1.7.13', true );

		// Core
		wp_enqueue_style( 'wdc-ui', plugins_url( "assets/css/ui$min.css", WDC_PLUGIN_FILE ), array(), WDC_VERSION );
		wp_enqueue_script( 'wdc-ui', plugins_url( "assets/js/ui$min.js", WDC_PLUGIN_FILE ), array( 'jquery', 'wp-util' ), WDC_VERSION, true );

		wp_localize_script( 'wdc-ui', 'wdc', array
		(
			'messages' => array
			(
				'notSaved' => __( 'Confirm unsaved changes.', 'wdc' ),
			),
		));
	}

	/**
	 * Template Scripts
	 */
	public static function template_scripts()
	{
		if ( 'widgets.php' != $GLOBALS['pagenow'] ) return;

		?>

		<script id="tmpl-wdc-ui" type="text/html">
			
			<div class="wdc-ui">

				<h1><?php _e( 'Widget Display Conditions', 'wdc' ); ?></h1>

				<form method="post">
				
					<?php wp_nonce_field( 'ui', WDC_NONCE_NAME ); ?>

					<input type="hidden" name="action" value="wdc_ui_update">
					<input type="hidden" name="widget" value="{{ data.widget }}">

					<div class="notice notice-info wdc-hide-if-conditions">
						<p><?php esc_html_e( __( 'No conditions set.', 'wdc' ) ); ?></p>
					</div>
					
					<h4 class="wdc-show-if-conditions"><?php _e( 'Show widget if', 'wdc' ); ?></h4>

					<div class="wdc-condition-groups"></div>

					<p>
						<button class="button wdc-add-condition-group" type="button"><?php esc_html_e( 'Add group', 'wdc' ); ?></button>
					</p>

					<p class="submit alignright">
						<span class="spinner"></span>
						<button class="button button-primary" type="submit" data-saved="<?php esc_attr_e( 'Saved', 'wdc' ); ?>"><?php esc_html_e( 'Save', 'wdc' ); ?></button>
					</p>

				</form>

			</div><!-- .wdc-ui -->

		</script>

		<script id="tmpl-wdc-condition-group" type="text/html">
			
			<div class="wdc-condition-group" data-id="{{ data.id }}">

				<table class="wdc-conditions"></table>

				<h4><?php _e( 'or', 'wdc' ); ?></h4>

			</div>
			
		</script>

		<script id="tmpl-wdc-condition" type="text/html">
			
			<tr class="wdc-condition" data-id="{{ data.id }}" data-group="{{ data.group }}">

				<td>
					<select class="wdc-param" name="conditions[{{ data.group }}][{{ data.id }}][param]">
						<?php echo wdc_get_dropdown_options( wdc_get_condition_param_field_items() ); ?>
					</select>
				</td>

				<td>
					<select class="wdc-operator" name="conditions[{{ data.group }}][{{ data.id }}][operator]"></select>
				</td>

				<td>
					<select class="wdc-value" name="conditions[{{ data.group }}][{{ data.id }}][value]"></select>
				</td>

				<td>
					<button class="button wdc-add-condition" type="button"><?php esc_html_e( 'and', 'wdc' ); ?></button>
				</td>

				<td>
					<button class="button-link dashicons-before dashicons-trash wdc-remove-condition" type="button">
						<span class="screen-reader-text"><?php esc_html_e( 'remove', 'wdc' ); ?></span> 
					</button>
				</td>

			</tr>
			
		</script>

		<?php
	}

	/**
	 * Doing ajax
	 *
	 * @return bool
	 */
	public static function doing_ajax()
	{
		return wp_doing_ajax() && check_admin_referer( 'ui', WDC_NONCE_NAME );
	}
}

WDC_UI::init();
