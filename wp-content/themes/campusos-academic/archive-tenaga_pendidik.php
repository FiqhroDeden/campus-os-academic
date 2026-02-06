<?php
/**
 * Archive Template: Tenaga Pendidik
 */
get_header();

$jabatan_labels = [
    'guru_besar'    => 'Guru Besar',
    'lektor_kepala' => 'Lektor Kepala',
    'lektor'        => 'Lektor',
    'asisten_ahli'  => 'Asisten Ahli',
];
?>
<div class="page-hero">
    <div class="container">
        <h1><?php post_type_archive_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'unpatti-academic' ); ?></a> &raquo;
            <?php post_type_archive_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="profile-grid">
                <?php while ( have_posts() ) : the_post();
                    $nidn      = get_post_meta( get_the_ID(), 'tenaga_pendidik_nidn', true );
                    $jabatan   = get_post_meta( get_the_ID(), 'tenaga_pendidik_jabatan_fungsional', true );
                    $keahlian  = get_post_meta( get_the_ID(), 'tenaga_pendidik_bidang_keahlian', true );
                    $email     = get_post_meta( get_the_ID(), 'tenaga_pendidik_email_dosen', true );
                ?>
                <div class="profile-card">
                    <div class="profile-photo">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium' ); ?>
                        <?php else : ?>
                            <div class="profile-placeholder"><span class="dashicons dashicons-admin-users"></span></div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <h3 class="profile-name"><?php the_title(); ?></h3>
                        <div class="profile-meta">
                            <?php if ( $jabatan && isset( $jabatan_labels[ $jabatan ] ) ) : ?>
                                <p class="meta-jabatan"><?php echo esc_html( $jabatan_labels[ $jabatan ] ); ?></p>
                            <?php endif; ?>
                            <?php if ( $nidn ) : ?>
                                <p class="meta-nip">NIDN: <?php echo esc_html( $nidn ); ?></p>
                            <?php endif; ?>
                            <?php if ( $keahlian ) : ?>
                                <p class="meta-keahlian"><?php echo esc_html( $keahlian ); ?></p>
                            <?php endif; ?>
                            <?php if ( $email ) : ?>
                                <p class="meta-email"><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ) ); ?>
        <?php else : ?>
            <p class="no-content"><?php esc_html_e( 'Belum ada data tenaga pendidik.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
