<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

/**
 * Widget Form
 */
function wdc_widget_form( $widget, $return, $instance )
{
	$rules = isset( $instance['wdc_rules'] ) ? (array) $instance['wdc_rules'] : array();

	?>

	<input type="hidden" class="wdc-rules" name="<?php echo $widget->get_field_name( 'wdc_rules' ); ?>" value="<?php echo esc_attr( json_encode( $rules ) ); ?>">

	<p>
		<button type="button" class="button wdc-settings-button"><?php _e( 'Display conditions', 'wdc' ); ?></button>
	</p>

	<?php
}

add_action( 'in_widget_form', 'wdc_widget_form', 99, 3 );

/**
 * Widget Update
 *
 * Called when a widget form has been submitted.
 */
function wdc_widget_update( $instance, $new_instance, $old_instance, $widget )
{
	if ( ! empty( $new_instance['wdc_rules'] ) ) 
	{
		$instance['wdc_rules'] = json_decode( $new_instance['wdc_rules'] );
	}

	else
	{
		$instance['wdc_rules'] = array();
	}

	return $instance;
}

add_action( 'widget_update_callback', 'wdc_widget_update', 10, 4 );

/**
 * Enqueue Scripts
 */
function wdc_enqueue_scripts( $hook )
{
	// Checks if widgets screen

	if ( $hook != 'widgets.php' )
	{
		return;
	}

	// Vendor
	wp_enqueue_script( 'wp-util' );
	wp_enqueue_script( 'featherlight', plugins_url( 'vendor/featherlight/featherlight.js', WDC_FILE ), array( 'jquery' ), '1.7.9', true );
	wp_enqueue_script( 'serialize-object', plugins_url( 'vendor/serialize-object/serialize-object.js', WDC_FILE ), array( 'jquery' ), false, true );

	wp_enqueue_script( 'select2', plugins_url( 'vendor/select2/js/select2.min.js', WDC_FILE ), array( 'jquery' ), '4.0.6', true );

	// Ours assets
	wp_enqueue_style( 'widget-display-rules', plugins_url( 'css/main.css', WDC_FILE ) );
	wp_enqueue_script( 'widget-display-rules', plugins_url( 'js/main.js', WDC_FILE ), array( 'jquery' ), false, true );
}

add_action( 'admin_enqueue_scripts', 'wdc_enqueue_scripts' );

/**
 * Print Scripts
 */
function wdc_print_scripts()
{
	// Checks if widgets screen

	$screen = get_current_screen();
	
	if ( $screen->id != 'widgets' ) 
	{
		return;
	}

	?>

	<script id="tmpl-wdc-settings" type="text/html">
		
		<div class="wdc-settings">

			<h1><?php _e( 'Display Conditions', 'wdc' ); ?></h1>

			<div class="notice notice-info wdc-hide-if-rules">
				<p><strong><?php _e( 'No conditions set.', 'wdc' ); ?></strong> <?php _e( 'Widget will be displayed on any page.', 'wdc' ); ?></p>
			</div>

			<div class="notice notice-info wdc-show-if-rules">
				<p><strong><?php _e( 'Widget will be displayed when following conditions are met.', 'wdc' ); ?></strong></p>
			</div>

			<form method="post">
				
				<div class="rule-groups"></div>

				<p>
					<button type="button" class="button add-rule-group"><?php _e( 'Add rule group', 'wdc' ); ?></button>
				</p>

				<p class="wdc-submit">
					<button type="submit" class="button button-primary"><?php _e( 'Update', 'wdc' ); ?></button>
				</p>

			</form>

		</div><!-- .wdc-settings -->

	</script>

	<script id="tmpl-wdc-rule-group" type="text/html">

		<div class="rule-group" data-id="{{ data.id }}">

			<h4><?php _e( 'or', 'wdc' ); ?></h4>

			<table class="rules"></table>

		</div><!-- .rule-group -->
		
	</script>

	<script id="tmpl-wdc-rule" type="text/html">
		
		<?php  

			$rules = WDC_API::get_rules();

		?>

		<tr class="rule" data-id="{{ data.id }}">
			<td class="param">
				<select name="rules[{{ data.group }}][{{ data.id }}][param]">
					<?php foreach ( $rules as $rule ) : ?>
					<option value="<?php echo esc_attr( get_class( $rule ) ); ?>"><?php echo esc_html( $rule->get_title() ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td class="operator">
				<select name="rules[{{ data.group }}][{{ data.id }}][operator]"></select>
			</td>
			<td class="value">
				<select name="rules[{{ data.group }}][{{ data.id }}][value]"></select>
			</td>
			<td class="and">
				<button class="button"><?php _e( 'And', 'wdc' ); ?></button>
			</td>
			<td class="remove">
				<button class="button-link button-link-delete"><?php _e( 'Remove', 'wdc' ); ?></button>
			</td>
		</tr><!-- .rule -->

	</script>

	<?php
}

add_action( 'admin_print_scripts', 'wdc_print_scripts' );

/**
 * Get Rule Parameter Values
 */
function wdc_load_rule()
{
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
	{
		return;
	}

	$param_id = isset( $_POST['param'] ) ? $_POST['param'] : '';

	$rule = WDC_API::get_rule( $param_id );

	// Checks if param is registered.

	if ( ! $rule )
	{
		wp_send_json_error( sprintf( __( "Invalid param '%s'.", 'wdr' ), $param_id ) );
	}

	$operators = $rule->get_operators();

	$operator_choices = array();

	foreach ( $operators as $operator ) 
	{
		$operator_choices[] = array
		(
			'id'   => get_class( $operator ),
			'text' => $operator->get_title()
		);
	}

	wp_send_json_success( array
	(
		'operators' => $operator_choices,
		'choices'   => $rule->choices()
	));
}

add_action( 'wp_ajax_wdc_load_rule', 'wdc_load_rule' );

