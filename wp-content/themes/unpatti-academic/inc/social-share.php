<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Menampilkan tombol berbagi sosial media.
 */
function unpatti_social_share() {
    if ( ! is_singular() ) return;

    $url   = urlencode( get_permalink() );
    $title = urlencode( get_the_title() );

    $facebook_url  = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
    $twitter_url   = 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title;
    $whatsapp_url  = 'https://api.whatsapp.com/send?text=' . $title . '%20' . $url;

    $btn_style = 'display:inline-block;padding:0.5rem 1rem;font-size:0.8125rem;font-weight:600;color:#fff;border-radius:4px;text-decoration:none;transition:opacity 0.2s;';

    echo '<div class="social-share" style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--unpatti-border, #e5e7eb);">';
    echo '<p style="font-weight:600;margin-bottom:0.75rem;">' . esc_html__( 'Bagikan:', 'unpatti-academic' ) . '</p>';
    echo '<div style="display:flex;flex-wrap:wrap;gap:0.5rem;">';

    // Facebook
    echo '<a href="' . esc_url( $facebook_url ) . '" target="_blank" rel="noopener noreferrer" style="' . $btn_style . 'background:#1877F2;">' . esc_html__( 'Facebook', 'unpatti-academic' ) . '</a>';

    // Twitter/X
    echo '<a href="' . esc_url( $twitter_url ) . '" target="_blank" rel="noopener noreferrer" style="' . $btn_style . 'background:#000;">' . esc_html__( 'Twitter / X', 'unpatti-academic' ) . '</a>';

    // WhatsApp
    echo '<a href="' . esc_url( $whatsapp_url ) . '" target="_blank" rel="noopener noreferrer" style="' . $btn_style . 'background:#25D366;">' . esc_html__( 'WhatsApp', 'unpatti-academic' ) . '</a>';

    // Copy Link
    echo '<button type="button" onclick="navigator.clipboard.writeText(\'' . esc_url( get_permalink() ) . '\').then(function(){alert(\'' . esc_attr__( 'Link berhasil disalin!', 'unpatti-academic' ) . '\');})" style="' . $btn_style . 'background:#6b7280;border:none;cursor:pointer;">' . esc_html__( 'Salin Link', 'unpatti-academic' ) . '</button>';

    echo '</div>';
    echo '</div>';
}
