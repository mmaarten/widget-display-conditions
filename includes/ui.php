<?php
/**
 * UI
 */

namespace wdc;

final class UI
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

	private function __construct(){}

	public function init()
	{
		add_action( 'admin_enqueue_scripts' , array( &$this, 'scripts' ) );
		add_action( 'admin_footer'          , array( &$this, 'template_scripts' ) );
		add_action( 'in_widget_form'        , array( &$this, 'widget_form' ), 999 );

		add_action( 'wp_ajax_wdc_ui_preload', array( &$this, 'preload' ) );
		add_action( 'wp_ajax_wdc_ui_update' , array( &$this, 'update' ) );
		add_action( 'wp_ajax_wdc_ui_get_condition_fields_items' , array( &$this, 'get_condition_fields_items' ) );
	}

	public function is_page()
	{
		return is_admin() && 'widgets.php' == $GLOBALS['pagenow'];
	}

	public function get_condition_fields_items()
	{
		// Check if ajax call

		if ( ! wp_doing_ajax() ) return;

		// Check nonce and referer

		check_admin_referer( 'ui', WDC_NONCE_NAME );

		// Get field items

		$condition_id = $_POST['param'];

		$items = array
		(
			'operator' => get_condition_operator_field_items( $condition_id ),
			'value'    => get_condition_value_field_items( $condition_id ),
		);

		// Response

		wp_send_json( $items );
	}

	public function widget_form( $widget )
	{
		// Output button that opens UI on click

		$button = sprintf( '<button class="button wdc-open-ui" type="button" data-widget="%s" data-noncename="%s" data-nonce="%s">%s</button>',
			esc_attr( $widget->id ), esc_attr( WDC_NONCE_NAME ), esc_attr( wp_create_nonce( 'ui' ) ), esc_html__( 'Display Conditions', 'wdc' ) );

		?>

		<p class="wdc-open-ui-wrap">
			<?php echo $button;  ?>
			<span class="spinner"></span>
		</p>

		<?php
	}

	public function preload()
	{
		// Check if ajax call

		if ( ! wp_doing_ajax() ) return;

		// Check nonce and referer

		check_admin_referer( 'ui', WDC_NONCE_NAME );

		// Get widget condition data

		$rules = get_widget_conditions( $_POST['widget'] );

		// Get field data

		$field_data = array();

		if ( is_array( $rules ) ) 
		{
			foreach ( $rules as $group ) 
			{
				foreach ( $group as $rule ) 
				{
					$condition_id = $rule['param'];

					if ( isset( $field_data[ $condition_id ] ) ) 
					{
						continue;
					}

					$field_data[ $condition_id ] = array
					(
						'operator' => get_condition_operator_field_items( $condition_id ),
						'value'    => get_condition_value_field_items( $condition_id ),
					);
				}
			}
		}

		// Response

		wp_send_json( array
		(
			'conditions' => $rules,
			'fieldData'  => $field_data,
		));
	}

	public function update()
	{
		// Check if ajax call

		if ( ! wp_doing_ajax() ) return;

		// Check nonce and referer

		check_admin_referer( 'ui', WDC_NONCE_NAME );

		// Update widget conditions data

		$rules = isset( $_POST['conditions'] ) ? (array) $_POST['conditions'] : array();

		$result = set_widget_conditions( $_POST['widget'], $rules );
 		
		// Response

		wp_send_json( $result );
	}

	public function scripts()
	{
		// Check if UI page
		if ( ! $this->is_page() ) return;

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'dashicons' );

		// Featherlight
		wp_enqueue_script( 'featherlight', plugins_url( "assets/js/featherlight$min.js", WDC_FILE ), array( 'jquery' ), '1.7.13', true );

		// Core
		wp_enqueue_style( 'wdc-ui', plugins_url( "assets/css/ui$min.css", WDC_FILE ), array(), WDC_VERSION );
		wp_enqueue_script( 'wdc-ui', plugins_url( "assets/js/ui$min.js", WDC_FILE ), array( 'jquery', 'wp-util' ), WDC_VERSION, true );
	}

	public function template_scripts()
	{
		// Check if UI page
		if ( ! $this->is_page() ) return;

		?>

		<script id="tmpl-wdc-ui" type="text/html">
			
			<div class="wdc-ui">

				<h1><?php esc_html_e( 'Widget Display Conditions', 'wdc' ); ?></h1>

				<form method="post">
					
					<?php wp_nonce_field( 'ui', WDC_NONCE_NAME ); ?>

					<input type="hidden" name="action" value="wdc_ui_update">
					<input type="hidden" name="widget" value="{{ data.widget }}">

					<div class="wdc-hide-if-conditions">
						<?php admin_notice( __( 'No conditions set.', 'wdc' ) ); ?>
					</div>

					<div class="wdc-show-if-conditions">
						<h4><?php esc_html_e( 'Show widget if', 'wdc' ); ?></h4>
					</div>

					<div class="wdc-condition-groups"></div>

					<p>
						<button class="button wdc-add-condition-group" type="button"><?php esc_html_e( 'Add Group', 'wdc' ); ?></button>
					</p>

					<p class="submit">
						<span class="spinner"></span>
						<input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e( 'Save', 'wdc' ); ?>" data-saved="<?php esc_attr_e( 'Saved', 'wdc' ); ?>">
					</p>

				</form>

			</div>

		</script>

		<script id="tmpl-wdc-condition-group" type="text/html">
			
			<div class="wdc-condition-group" data-id="{{ data.id }}">
			
				<table class="wdc-conditions"></table>

				<h4><?php esc_html_e( 'or', 'wdc' ); ?></h4>

			</div>

		</script>

		<script id="tmpl-wdc-condition" type="text/html">
			
			<tr class="wdc-condition" data-id="{{ data.id }}" data-group="{{ data.group }}">

				<td>
					<select class="wdc-param" name="conditions[{{ data.group }}][{{ data.id }}][param]">
						<?php echo get_dropdown_options( get_condition_param_field_items() ); ?>
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
						<span class="screen-reader-text"><?php esc_html_e( 'Remove', 'wdc' ); ?></span>
					</button>
				</td>

			</tr>

		</script>

		<?php
	}
}

UI::get_instance()->init();
