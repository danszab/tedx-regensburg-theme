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
		// Dropdown animation: using custom CSS .nav-dropdown-wrapper to ensure visibility on all environments
		$output .= "\n$indent<div class=\"absolute left-0 top-full pt-2 z-50 nav-dropdown-wrapper\"><ul class=\"sub-menu w-48 bg-tedx-dark border border-white/10 rounded-xl shadow-xl py-2\">\n";
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
		$link_classes = 'inline-flex items-center py-2 text-base font-medium transition-all duration-200 nav-link nav-link-anim ';
		if ( $depth === 0 ) {
			if ( $is_active ) {
				$link_classes .= 'text-tedx-red font-bold active-nav-link ';
			} else {
				$link_classes .= 'text-white/90 hover:text-white ';
			}
		} else {
			$link_classes .= 'w-full px-4 py-2 text-sm text-gray-300 hover:text-white ';
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
		
		if ( in_array( 'menu-item-has-children', $classes ) && $depth === 0 ) {
			$title .= ' <svg class="w-4 h-4 ml-1 opacity-70 nav-dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>';
		}

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

class TEDx_Tailwind_Mobile_Nav_Walker extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		// Initially hidden list for mobile accordion
		$output .= "\n$indent<ul class=\"sub-menu hidden flex-col gap-3 pl-4 pt-3 mt-1 border-l-2 border-white/10\">\n";
	}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$menu_item = $data_object;
		$classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
		$classes[] = 'menu-item-' . $menu_item->ID;
		
		$is_active = in_array( 'current-menu-item', $classes ) || in_array( 'current_page_item', $classes );

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= '<li' . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
		$atts['target'] = ! empty( $menu_item->target ) ? $menu_item->target : '';
		$atts['rel']    = ! empty( $menu_item->xfn ) ? $menu_item->xfn : '';
		$atts['href']   = ! empty( $menu_item->url ) ? $menu_item->url : '';

		$link_classes = 'font-medium transition-all inline-block nav-link nav-link-anim mobile-nav-parent ';
		if ( $depth === 0 ) {
			if ( $is_active ) {
				$link_classes .= 'text-xl text-tedx-red font-bold active-nav-link ';
			} else {
				$link_classes .= 'text-xl text-white/90 hover:text-white ';
			}
		} else {
			if ( $is_active ) {
				$link_classes .= 'text-lg text-tedx-red font-bold active-nav-link ';
			} else {
				$link_classes .= 'text-lg text-white/60 hover:text-white ';
			}
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

		if ( in_array( 'menu-item-has-children', $classes ) && $depth === 0 ) {
			$title .= ' <svg class="mobile-dropdown-arrow w-5 h-5 ml-1 inline-block opacity-70 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>';
		}

		$item_output  = isset( $args->before ) ? $args->before : '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . $title . ( isset( $args->link_after ) ? $args->link_after : '' );
		$item_output .= '</a>';
		$item_output .= isset( $args->after ) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
