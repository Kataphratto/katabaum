<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );
function enqueue_parent_theme_style() {
	wp_enqueue_style( 'parent-style', get_stylesheet_directory_uri() . '/style.css' );
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );

// Disable reviews from single tabs

add_filter( 'woocommerce_product_tabs', 'sb_woo_remove_reviews_tab', 98 );
function sb_woo_remove_reviews_tab( $tabs ) {

	unset( $tabs['reviews'] );

	return $tabs;
}


//if($sales_price_to != "") :

add_filter( 'woocommerce_get_price_html', 'omniwp_credit_dollars_price', 10, 2 );
function omniwp_credit_dollars_price( $price, $product ) {
	$pricing_rule_sets = get_post_meta( $product->get_id(), '_pricing_rules', true );


	if ( $pricing_rule_sets
	     && is_array( $pricing_rule_sets )
	     && sizeof( $pricing_rule_sets ) ) {
		$pricing_rule_sets = array_shift( $pricing_rule_sets );

		$execute_rules = true;

		$from_date = empty( $pricing_rule_sets['date_from'] ) ? false : strtotime( date_i18n( 'Y-m-d 00:00:00', strtotime( $pricing_rule_sets['date_from'] ), false ) );
		$to_date   = empty( $pricing_rule_sets['date_to'] ) ? false : strtotime( date_i18n( 'Y-m-d 00:00:00', strtotime( $pricing_rule_sets['date_to'] ), false ) );
		$now       = current_time( 'timestamp' );

		if ( $from_date && $to_date && ! ( $now >= $from_date && $now <= $to_date ) ) {
			$execute_rules = false;
		} elseif ( $from_date && ! $to_date && ! ( $now >= $from_date ) ) {
			$execute_rules = false;
		} elseif ( $to_date && ! $from_date && ! ( $now <= $to_date ) ) {
			$execute_rules = false;
		}

		if ( ! $execute_rules ) {
			return $price;
		}

		ob_start();
		?>

        <table>
            <thead>
            <tr>
                <th><?php _e( 'Quantity', 'omniwp_core_functionality' ) ?></th>
                <th><?php _e( 'Price', 'omniwp_core_functionality' ) ?></th>
            </tr>
            </thead>
			<?php
			foreach ( $pricing_rule_sets['rules'] as $key => $value ) {
				if ( '*' == $pricing_rule_sets['rules'][ $key ]['to'] ) {
					?>
                    <tr>
                        <td><?php printf( __( '%s units or more', 'omniwp_core_functionality' ), $pricing_rule_sets['rules'][ $key ]['from'] ) ?></td>
                        <td><?php echo wc_price( $pricing_rule_sets['rules'][ $key ]['amount'] ); ?></td>
                    </tr>
					<?php
				} else {
					?>
                    <tr>
                        <td><?php printf( __( 'From %s to %s units', 'omniwp_core_functionality' ), $pricing_rule_sets['rules'][ $key ]['from'], $pricing_rule_sets['rules'][ $key ]['to'] ) ?></td>
                        <td><?php echo wc_price( $pricing_rule_sets['rules'][ $key ]['amount'] ); ?></td>
                    </tr>
					<?php
				}
			}
			?>
        </table>
		<?php
		$price = ob_get_clean();
	}

	return $price;
}

function wooc_extra_register_fields() {?>
	<label for="reg_billing_check_box">Facendo clic su 'Registrati', accetti di aver preso visione <a ref="/privacy">del testo sul trattamento dei dati</a>.</label>
 <?php
 }
add_action( 'woocommerce_register_form_end', 'wooc_extra_register_fields' );

function my_woocommerce_before_checkout_process() {
    remove_filter( 'woocommerce_registration_errors', array('WC_Ncr_Registration_Captcha', 'validate_captcha_wc_registration'), 10 );
}
add_action('woocommerce_before_checkout_process', 'my_woocommerce_before_checkout_process');