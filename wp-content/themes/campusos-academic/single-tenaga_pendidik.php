<?php
/**
 * Single Template: Tenaga Pendidik (Staff Profile)
 */
get_header();

while ( have_posts() ) : the_post();
    $nidn      = get_post_meta( get_the_ID(), '_tenaga_pendidik_nidn', true );
    $nip       = get_post_meta( get_the_ID(), '_tenaga_pendidik_nip', true );
    $jabatan   = get_post_meta( get_the_ID(), '_tenaga_pendidik_jabatan_fungsional', true );
    $status    = get_post_meta( get_the_ID(), '_tenaga_pendidik_status_kepegawaian', true );
    $sertif    = get_post_meta( get_the_ID(), '_tenaga_pendidik_sertifikasi', true );
    $keahlian  = get_post_meta( get_the_ID(), '_tenaga_pendidik_bidang_keahlian', true );
    $pendidikan = get_post_meta( get_the_ID(), '_tenaga_pendidik_pendidikan', true );
    $email     = get_post_meta( get_the_ID(), '_tenaga_pendidik_email_dosen', true );
    $link      = get_post_meta( get_the_ID(), '_tenaga_pendidik_link_profil', true );
    $riwayat   = get_post_meta( get_the_ID(), '_tenaga_pendidik_riwayat_pendidikan', true );
    $custom    = get_post_meta( get_the_ID(), '_tenaga_pendidik_custom_fields', true );

    $jabatan_labels = [
        'guru_besar'    => 'Guru Besar',
        'lektor_kepala' => 'Lektor Kepala',
        'lektor'        => 'Lektor',
        'asisten_ahli'  => 'Asisten Ahli',
    ];
    $status_labels = [
        'pns'     => 'PNS',
        'non_pns' => 'Non-PNS',
        'kontrak' => 'Kontrak',
    ];
?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</div>

<main id="primary" class="site-main">
    <div class="container">
        <article class="single-tenaga-pendidik">

            <div class="tp-profile-grid">
                <div class="tp-photo-col">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="tp-photo">
                            <?php the_post_thumbnail( 'medium_large' ); ?>
                        </div>
                    <?php else : ?>
                        <div class="tp-photo tp-photo-placeholder">
                            <span class="dashicons dashicons-admin-users"></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $email ) : ?>
                        <div class="tp-email">
                            <a href="mailto:<?php echo esc_attr( $email ); ?>">
                                <span class="dashicons dashicons-email-alt"></span>
                                <?php echo esc_html( $email ); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ( $link ) : ?>
                        <div class="tp-link">
                            <a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener noreferrer">
                                <span class="dashicons dashicons-admin-links"></span>
                                <?php esc_html_e( 'Lihat Profil Lengkap', 'campusos-academic' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tp-info-col">
                    <table class="tp-info-table">
                        <?php if ( $jabatan ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'Jabatan Fungsional', 'campusos-academic' ); ?></th>
                                <td><?php echo esc_html( $jabatan_labels[ $jabatan ] ?? $jabatan ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $nidn ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'NIDN', 'campusos-academic' ); ?></th>
                                <td><?php echo esc_html( $nidn ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $nip ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'NIP', 'campusos-academic' ); ?></th>
                                <td><?php echo esc_html( $nip ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $status ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'Status Kepegawaian', 'campusos-academic' ); ?></th>
                                <td><?php echo esc_html( $status_labels[ $status ] ?? $status ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $sertif ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'Sertifikasi', 'campusos-academic' ); ?></th>
                                <td><?php echo esc_html( $sertif ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $keahlian ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'Bidang Keahlian', 'campusos-academic' ); ?></th>
                                <td><?php echo esc_html( $keahlian ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $pendidikan ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'Pendidikan Terakhir', 'campusos-academic' ); ?></th>
                                <td><?php echo esc_html( $pendidikan ); ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <?php if ( is_array( $riwayat ) && ! empty( $riwayat ) ) : ?>
                <div class="tp-section">
                    <h2 class="tp-section-title"><?php esc_html_e( 'Riwayat Pendidikan', 'campusos-academic' ); ?></h2>
                    <ul class="tp-riwayat-list">
                        <?php foreach ( $riwayat as $row ) : ?>
                            <li>
                                <strong><?php echo esc_html( $row['jenjang'] ?? '' ); ?></strong>
                                <?php if ( ! empty( $row['universitas'] ) || ! empty( $row['prodi'] ) ) : ?>
                                    &mdash;
                                    <?php echo esc_html( $row['universitas'] ?? '' ); ?>
                                    <?php if ( ! empty( $row['prodi'] ) ) : ?>
                                        <span class="tp-riwayat-prodi">(<?php echo esc_html( $row['prodi'] ); ?>)</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ( is_array( $custom ) && ! empty( $custom ) ) : ?>
                <?php foreach ( $custom as $item ) : ?>
                    <?php if ( empty( $item['label'] ) && empty( $item['value'] ) ) continue; ?>
                    <div class="tp-section">
                        <h2 class="tp-section-title"><?php echo esc_html( $item['label'] ?? '' ); ?></h2>
                        <div class="tp-custom-value"><?php echo wp_kses_post( nl2br( esc_html( $item['value'] ?? '' ) ) ); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </article>
    </div>
</main>
<?php
endwhile;
get_footer();
?>
