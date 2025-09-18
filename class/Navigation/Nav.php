<?php
namespace Vgtech\ThemeVgtech\Navigation;

use Walker_Nav_Menu;

class Nav extends Walker_Nav_Menu {
    public function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args = [], &$output = '' ) {
        if ( isset( $args[0] ) && is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[ $element->ID ] );
        }
        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent  = str_repeat("\t", $depth);
        $submenu = $depth > 0 ? ' dropdown-submenu' : '';
        $output .= "\n$indent<ul class=\"dropdown-menu$submenu\" role=\"menu\">\n";
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $has_child = isset($args->has_children) && $args->has_children;

        $li_classes = ['menu-item'];
        if ($depth === 0) {
            $li_classes[] = $has_child ? 'nav-item dropdown' : 'nav-item';
        } else {
            if ($has_child) $li_classes[] = 'dropdown-submenu dropdown';
        }

        $output .= '<li class="' . esc_attr(implode(' ', array_filter($li_classes))) . '">';

        $atts = [
            'href'  => !empty($item->url) ? $item->url : '#',
            'class' => ($depth === 0) ? 'nav-link' : 'dropdown-item',
        ];
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);

        if ($has_child) {
            $caret_button = sprintf(
                '<button class="nav-vgtech btn btn-link p-0 ms-2 align-middle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        aria-label="%s">
                    <i class="bi bi-chevron-down"></i>
                </button>',
                esc_attr__('Mở menu con', 'vgtech')
            );



            if ($depth === 0) {
                $output .= '<div class="d-flex align-items-center">';
                $output .= '<a' . $attributes . '>' . $title . '</a>';
                $output .= $caret_button;
                $output .= '</div>';
            } else {
                $output .= '<div class="d-flex align-items-center px-3 py-2 dropdown-split">';
                $output .= '<a' . $attributes . '>' . $title . '</a>';
                $output .= $caret_button;
                $output .= '</div>';
            }
        } else {
            $output .= '<a' . $attributes . '>' . $title . '</a>';
        }
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}
