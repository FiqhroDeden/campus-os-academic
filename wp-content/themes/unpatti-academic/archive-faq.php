<?php
/**
 * Archive Template: FAQ
 */
get_header();

$faq_query = new WP_Query( array(
    'post_type'      => 'faq',
    'posts_per_page' => -1,
    'meta_key'       => 'faq_urutan_faq',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
) );
?>
<div class="page-hero">
    <div class="container">
        <h1><?php esc_html_e( 'Frequently Asked Questions', 'unpatti-academic' ); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'unpatti-academic' ); ?></a> &raquo;
            FAQ
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( $faq_query->have_posts() ) :
            $categories = array();
            while ( $faq_query->have_posts() ) : $faq_query->the_post();
                $kategori = get_post_meta( get_the_ID(), 'faq_kategori_faq', true );
                if ( ! $kategori ) $kategori = 'Umum';
                if ( ! isset( $categories[ $kategori ] ) ) {
                    $categories[ $kategori ] = array();
                }
                $categories[ $kategori ][] = array(
                    'question' => get_the_title(),
                    'answer'   => get_the_content(),
                );
            endwhile;
            wp_reset_postdata();
        ?>
            <div class="faq-container">
                <?php foreach ( $categories as $cat_name => $items ) : ?>
                    <div class="faq-category">
                        <h2 class="faq-category-title"><?php echo esc_html( $cat_name ); ?></h2>
                        <div class="faq-accordion">
                            <?php foreach ( $items as $index => $item ) : ?>
                                <div class="faq-item">
                                    <button class="faq-question" aria-expanded="false">
                                        <span class="faq-question-text"><?php echo esc_html( $item['question'] ); ?></span>
                                        <span class="faq-toggle"><span class="dashicons dashicons-plus-alt2"></span></span>
                                    </button>
                                    <div class="faq-answer" hidden>
                                        <div class="faq-answer-content"><?php echo wp_kses_post( $item['answer'] ); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="no-content"><?php esc_html_e( 'Belum ada FAQ.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
