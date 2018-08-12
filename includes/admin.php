<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

/**
 * Widget Form
 */
function wdc_widget_form( $widget, $return, $instance )
{
	$conditions = isset( $instance['wdc_conditions'] ) ? (array) $instance['wdc_conditions'] : array();

	?>

	<input type="hidden" class="wdc-conditions" name="<?php echo $widget->get_field_name( 'wdc_conditions' ); ?>" value="<?php echo esc_attr( json_encode( $conditions ) ); ?>">

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
	if ( ! empty( $new_instance['wdc_conditions'] ) ) 
	{
		$instance['wdc_conditions'] = json_decode( $new_instance['wdc_conditions'] );
	}

	else
	{
		$instance['wdc_conditions'] = array();
	}

	return $instance;
}

add_action( 'widget_update_callback', 'wdc_widget_update', 10, 4 );

/**
 * Enqueue Scripts
 */
function wdc_admin_enqueue_scripts( $hook )
{
	// Checks if widgets screen

	if ( $hook != 'widgets.php' )
	{
		return;
	}

	// Vendor
	wp_enqueue_style( 'dashicons' );

	wp_enqueue_script( 'wp-util' );
	wp_enqueue_script( 'featherlight', plugins_url( 'vendor/featherlight/featherlight.js', WDC_FILE ), array( 'jquery' ), '1.7.9', true );
	wp_enqueue_script( 'serialize-object', plugins_url( 'vendor/serialize-object/serialize-object.js', WDC_FILE ), array( 'jquery' ), false, true );

	wp_enqueue_script( 'select2', plugins_url( 'vendor/select2/js/select2.min.js', WDC_FILE ), array( 'jquery' ), '4.0.6', true );

	// Ours assets
	wp_enqueue_style( 'widget-display-conditions', plugins_url( 'css/main.css', WDC_FILE ) );
	wp_enqueue_script( 'widget-display-conditions', plugins_url( 'js/main.js', WDC_FILE ), array( 'jquery' ), false, true );
}

add_action( 'admin_enqueue_scripts', 'wdc_admin_enqueue_scripts' );

/**
 * Print Scripts
 */
function wdc_admin_print_scripts()
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

			<h1><?php _e( 'Widget Display Conditions', 'wdc' ); ?></h1>

			<section class="wdc-loader">
				<p><strong><?php _e( 'Gathering data…', 'wdc' ); ?></strong></p>
			</section><!-- .wdc-loader -->

			<section class="wdc-main">
				
				<div class="notice notice-info wdc-hide-if-conditions">
					<p><strong><?php _e( 'No conditions set.', 'wdc' ); ?></strong> <?php _e( 'Widget will be displayed on any page.', 'wdc' ); ?></p>
				</div>

				<div class="notice notice-info wdc-show-if-conditions">
					<p><strong><?php _e( 'Widget will be displayed when following conditions are met.', 'wdc' ); ?></strong></p>
				</div>

				<form method="post">
					
					<div class="condition-groups"></div>

					<p>
						<button type="button" class="button add-condition-group"><?php _e( 'Add condition group', 'wdc' ); ?></button>
					</p>

					<p class="wdc-submit">
						<button type="submit" class="button button-primary"><?php _e( 'Update', 'wdc' ); ?></button>
					</p>

				</form>

			</section><!-- .wdc-main -->

		</div><!-- .wdc-settings -->

	</script>

	<script id="tmpl-wdc-condition-group" type="text/html">

		<div class="condition-group" data-id="{{ data.id }}">

			<h4><?php _e( 'or', 'wdc' ); ?></h4>

			<table class="conditions"></table>

		</div><!-- .condition-group -->
		
	</script>

	<script id="tmpl-wdc-condition" type="text/html">
		<?php

		$categories = wdc_get_categories();

		?>
		<tr class="condition" data-id="{{ data.id }}">
			
			<td class="param">
				<select name="conditions[{{ data.group }}][{{ data.id }}][param]">
					<?php foreach ( $categories as $category ) : 

						$conditions = wdc_get_conditions( $category['id'] );

						if ( ! count( $conditions ) ) 
						{
							continue;
						}

					?>
					<optgroup label="<?php echo esc_attr( $category['title'] ); ?>">
						<?php foreach ( $conditions as $condition ) : ?>
						<option value="<?php echo esc_attr( $condition->id ); ?>"><?php echo esc_html( $condition->title ); ?></option>
						<?php endforeach; ?>
					</optgroup>
					<?php endforeach; ?>
				</select>
			</td>
			<td class="operator">
				<select name="conditions[{{ data.group }}][{{ data.id }}][operator]"></select>
			</td>
			<td class="value">
				<select name="conditions[{{ data.group }}][{{ data.id }}][value]"></select>
			</td>
			<td class="and">
				<button type="button" class="button" title="<?php esc_attr_e( 'Add condition', 'wdc' ); ?>"><?php _e( 'And', 'wdc' ); ?></button>
			</td>
			<td class="remove">
				<button type="button" class="button-link dashicons-before dashicons-trash" title="<?php esc_attr_e( 'Remove condition', 'wdc' ); ?>"><span class="screen-reader-text"><?php _e( 'Remove', 'wdc' ); ?></span></button>
			</td>
			<td class="loader"></td>
		</tr><!-- .condition -->

	</script>

	<?php
}

add_action( 'admin_print_scripts', 'wdc_admin_print_scripts' );

/**
 * Get Rule Parameter Values
 */
function wdc_get_param_items()
{
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
	{
		return;
	}

	$param_id = isset( $_POST['param'] ) ? $_POST['param'] : '';

	$items = array();

	if ( ! empty( $param_id ) ) 
	{
		foreach ( (array) $param_id as $condition_id ) 
		{
			$condition = wdc_get_condition( $condition_id );

			if ( ! $condition ) 
			{
				continue;
			}

			/**
			 * Operators
			 * -------------------------------------------------------
			 */

			$operators = $condition->operators;

			$operator_choices = array();

			foreach ( $operators as $operator_id ) 
			{
				$operator = wdc_get_operator( $operator_id );

				if ( ! $operator ) 
				{
					continue;
				}

				$operator_choices[] = array
				(
					'id'   => $operator->id,
					'text' => $operator->title
				);
			}

			/**
			 * Values
			 * -------------------------------------------------------
			 */

			$values = $condition->get_values();

			/* ---------------------------------------------------- */
			
			$items[ $condition->id ] = array
			(
				'operators' => $operator_choices,
				'values'    => $values
			);
		}
	}

	wp_send_json_success( $items );
}

add_action( 'wp_ajax_wdc_get_param_items', 'wdc_get_param_items' );

