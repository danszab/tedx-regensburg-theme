<?php
/**
 * Custom Tailwind CSS Navigation Walker for TEDx Regensburg
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TEDx_Tailwind_Nav_Walker extends Walker_Nav_Menu {
	/**
	 * Start Level (sub-menu)
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		// Removed mt-2 to prevent gap when hovering, added pt-2 instead. Replaced arbitrary bg with standard bg-tedx-dark.
		$output .= "\n$indent<div class=\"absolute left-0 top-full pt-2 hidden group-hover:block z-50\"><ul class=\"sub-menu w-48 bg-tedx-dark border border-white/10 rounded-xl shadow-xl py-2 transition-all\">\n";
	}

	/**
	 * Start Element
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$menu_item = $data_object;
		$classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
		$classes[] = 'menu-item-' . $menu_item->ID;
		
		$is_active = in_array( 'current-menu-item', $classes ) || in_array( 'current_page_item', $classes );

		if ( $depth === 0 ) {
			$classes[] = 'relative group';
		}

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= '<li' . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
		$atts['target'] = ! empty( $menu_item->target ) ? $menu_item->target : '';
		$atts['rel']    = ! empty( $menu_item->xfn ) ? $menu_item->xfn : '';
		$atts['href']   = ! empty( $menu_item->url ) ? $menu_item->url : '';

		// Tailwind link classes matching Figma text buttons
		$link_classes = 'inline-flex items-center px-4 py-2.5 rounded-xl text-base font-medium transition-all duration-200 ';
		if ( $depth === 0 ) {
			if ( $is_active ) {
				$link_classes .= 'text-white bg-white/10 ';
			} else {
				$link_classes .= 'text-white/90 hover:text-white hover:bg-white/10 ';
			}
		} else {
			$link_classes .= 'w-full px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 ';
		}

		$atts['class'] = $link_classes;

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

		$item_output  = isset( $args->before ) ? $args->before : '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . $title . ( isset( $args->link_after ) ? $args->link_after : '' );
		$item_output .= '</a>';
		$item_output .= isset( $args->after ) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
	}

	/**
	 * End Level (sub-menu)
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul></div>\n";
	}

	/**
	 * End Element
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
