<?php 
/**
 * UI
 */

namespace wdc;

/**
 * Widget Form
 *
 * @param WP_Widget $widget
 */
function ui_widget_form( $widget )
{
	// Output button to open UI
	printf( '<p><button class="button wdc-open-ui" type="button" data-widget="%s" data-noncename="%s" data-nonce="%s">%s</button></p>',
		esc_attr( $widget->id ), esc_attr( WDC_NONCE_NAME ), esc_attr( wp_create_nonce( 'ui' ) ), esc_html__( 'Display conditions', 'wdc' ) );
}

add_action( 'in_widget_form', __NAMESPACE__ . '\ui_widget_form', 999 );

function ui_get_condition_field_items()
{
	// Check if ajax call
	if ( ! wp_doing_ajax() ) return;

	// Check if request is comming from the right place
	check_admin_referer( 'ui', WDC_NONCE_NAME );

	$items = get_condition_field_items( $_POST['param'] );

	// Response
	wp_send_json( $items );
}

add_action( 'wp_ajax_wdc_ui_get_condition_field_items', __NAMESPACE__ . '\ui_get_condition_field_items' );

/**
 * Preload
 */
function ui_preload()
{
	// Check if ajax call
	if ( ! wp_doing_ajax() ) return;

	// Check if request is comming from the right place
	check_admin_referer( 'ui', WDC_NONCE_NAME );

	$conditions = get_widget_conditions( $_POST['widget'] );

	$field_items = array();

	if ( is_array( $conditions ) ) 
	{
		foreach ( $conditions as $group ) 
		{
			foreach ( $group as $condition ) 
			{
				$param = $condition['param'];

				if ( ! isset( $field_items[ $param ] ) ) 
				{
					$field_items[ $param ] = get_condition_field_items( $param );
				}
			}
		}
	}

	// Response
	wp_send_json( array
	(
		'conditions' => $conditions,
		'fieldItems' => $field_items,
	));
}

add_action( 'wp_ajax_wdc_ui_preload', __NAMESPACE__ . '\ui_preload' );

/**
 * Update
 */
function ui_update()
{
	// Check if ajax call
	if ( ! wp_doing_ajax() ) return;

	// Check if request is comming from the right place
	check_admin_referer( 'ui', WDC_NONCE_NAME );

	$conditions = isset( $_POST['conditions'] ) ? $_POST['conditions'] : array();

	$result = set_widget_conditions( $_POST['widget'], $conditions );

	// Response
	wp_send_json( $result );
}

add_action( 'wp_ajax_wdc_ui_update', __NAMESPACE__ . '\ui_update' );

/**
 * Scripts
 */
function ui_scripts()
{
	if ( 'widgets.php' != $GLOBALS['pagenow'] ) 
	{
		return;
	}

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( 'dashicons' );

	// Featherlight
	wp_enqueue_script( 'featherlight', plugins_url( "assets/js/featherlight$min.js", WDC_FILE ), array( 'jquery' ), '1.7.13', true );

	// Core
	wp_enqueue_style( 'wdc-ui', plugins_url( "assets/css/ui$min.css", WDC_FILE ), array(), WDC_VERSION );
	wp_enqueue_script( 'wdc-ui', plugins_url( "assets/js/ui$min.js", WDC_FILE ), array( 'jquery', 'wp-util' ), WDC_VERSION, true );
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\ui_scripts' );

/**
 * Template scripts
 */
function ui_template_scripts()
{
	if ( 'widgets.php' != $GLOBALS['pagenow'] ) 
	{
		return;
	}

	?>

	<script id="tmpl-wdc-ui" type="text/html">
		
		<div class="wdc-ui">

			<h1><?php _e( 'Widget Display Conditions', 'wdc' ); ?></h1>

			<div class="wdc-show-if-loading">
				<?php admin_notice( __( 'Gathering data…', 'wdc' ) . '<span class="spinner is-active"></span>' ); ?>
			</div>

			<div class="wdc-hide-if-loading">

				<form method="post">
				
					<?php wp_nonce_field( 'ui', WDC_NONCE_NAME ); ?>

					<input type="hidden" name="action" value="wdc_ui_update">
					<input type="hidden" name="widget" value="{{ data.widget }}">

					<div class="wdc-hide-if-conditions">
						<?php admin_notice( __( 'No conditions set.', 'wdc' ) ); ?>
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

			</div>
			

		</div>

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
					<?php echo get_dropdown_options( get_param_field_items() ); ?>
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

add_action( 'admin_footer', __NAMESPACE__ . '\ui_template_scripts' );
