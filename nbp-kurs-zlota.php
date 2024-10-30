<?php

/*
Plugin Name: NBP Kurs Złota
Description: NPB Kurs złota
Version:     1.0.0
Author:      Paweł Rudnicki
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: nbp-kurs-zlota
*/

include_once(dirname(__FILE__) . '/class.nbp-gold-widget.php' );

if ( ! function_exists('nbp_gold_load_widget') ) {
	function nbp_gold_load_widget() {
		register_widget( 'NBP_Gold_Widget' );
	}
}

add_action( 'widgets_init', 'nbp_gold_load_widget' );