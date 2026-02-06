<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<section class="homepage-section homepage-agenda">
    <div class="container">
        <h2><?php esc_html_e( 'Agenda', 'unpatti-academic' ); ?></h2>
        <div class="posts-grid">
            <?php
            $items = new WP_Query( [
                'post_type'      => 'agenda',
                'posts_per_page' => 6,
                'post_status'    => 'publish',
                'meta_key'       => '_agenda_tanggal_mulai_agenda',
                'orderby'        => 'meta_value',
                'order'          => 'DESC',
            ] );
            while ( $items->have_posts() ) : $items->the_post();
            $tanggal_mulai = get_post_meta( get_the_ID(), '_agenda_tanggal_mulai_agenda', true );
            ?>
            <div class="card">
                <div class="card-body">
                    <?php if ( $tanggal_mulai ) : ?>
                        <span class="card-date"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $tanggal_mulai ) ) ); ?></span>
                    <?php endif; ?>
                    <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
