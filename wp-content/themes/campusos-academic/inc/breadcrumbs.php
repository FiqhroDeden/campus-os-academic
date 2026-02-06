<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Menampilkan breadcrumbs dengan schema.org markup.
 */
function campusos_breadcrumbs() {
    if ( is_front_page() ) return;

    $sep   = ' <span class="breadcrumb-sep">&raquo;</span> ';
    $pos   = 1;
    $items = [];

    // Home
    $items[] = campusos_breadcrumb_item( esc_html__( 'Beranda', 'campusos-academic' ), home_url( '/' ), $pos++ );

    if ( is_404() ) {
        $items[] = campusos_breadcrumb_item( esc_html__( '404 - Halaman Tidak Ditemukan', 'campusos-academic' ), '', $pos++ );
    } elseif ( is_search() ) {
        /* translators: %s: search query */
        $items[] = campusos_breadcrumb_item( sprintf( esc_html__( 'Pencarian: %s', 'campusos-academic' ), get_search_query() ), '', $pos++ );
    } elseif ( is_post_type_archive() ) {
        $pt_obj = get_queried_object();
        if ( $pt_obj ) {
            $items[] = campusos_breadcrumb_item( $pt_obj->label, '', $pos++ );
        }
    } elseif ( is_singular() ) {
        $post_type = get_post_type();
        if ( $post_type && $post_type !== 'post' && $post_type !== 'page' ) {
            $pt_obj = get_post_type_object( $post_type );
            if ( $pt_obj && $pt_obj->has_archive ) {
                $items[] = campusos_breadcrumb_item( $pt_obj->labels->name, get_post_type_archive_link( $post_type ), $pos++ );
            } elseif ( $pt_obj ) {
                $items[] = campusos_breadcrumb_item( $pt_obj->labels->name, '', $pos++ );
            }
        } elseif ( $post_type === 'post' ) {
            $page_for_posts = get_option( 'page_for_posts' );
            if ( $page_for_posts ) {
                $items[] = campusos_breadcrumb_item( get_the_title( $page_for_posts ), get_permalink( $page_for_posts ), $pos++ );
            }
        }

        // Page ancestors
        if ( is_page() ) {
            $ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
            foreach ( $ancestors as $ancestor_id ) {
                $items[] = campusos_breadcrumb_item( get_the_title( $ancestor_id ), get_permalink( $ancestor_id ), $pos++ );
            }
        }

        $items[] = campusos_breadcrumb_item( get_the_title(), '', $pos++ );
    } elseif ( is_archive() ) {
        $items[] = campusos_breadcrumb_item( get_the_archive_title(), '', $pos++ );
    }

    echo '<nav class="campusos-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'campusos-academic' ) . '">';
    echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList" class="breadcrumb-list">';
    echo implode( $sep, $items );
    echo '</ol></nav>';
}

/**
 * Membuat satu item breadcrumb dengan schema.org markup.
 */
function campusos_breadcrumb_item( string $name, string $url, int $position ): string {
    $html  = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    if ( $url ) {
        $html .= '<a itemprop="item" href="' . esc_url( $url ) . '"><span itemprop="name">' . $name . '</span></a>';
    } else {
        $html .= '<span itemprop="name">' . $name . '</span>';
    }
    $html .= '<meta itemprop="position" content="' . (int) $position . '" />';
    $html .= '</li>';
    return $html;
}
