<?php
namespace UNPATTI\Core\Frontend;

if ( ! defined( 'ABSPATH' ) ) exit;

class Shortcodes {

    public function init() {
        // CPT Shortcodes
        add_shortcode( 'unpatti_tenaga_pendidik', [ $this, 'tenaga_pendidik' ] );
        add_shortcode( 'unpatti_pimpinan', [ $this, 'pimpinan' ] );
        add_shortcode( 'unpatti_sambutan_kaprodi', [ $this, 'sambutan_kaprodi' ] );
        add_shortcode( 'unpatti_agenda', [ $this, 'agenda' ] );
        add_shortcode( 'unpatti_pengumuman', [ $this, 'pengumuman' ] );
        add_shortcode( 'unpatti_faq', [ $this, 'faq' ] );
        add_shortcode( 'unpatti_galeri', [ $this, 'galeri' ] );
        add_shortcode( 'unpatti_dokumen', [ $this, 'dokumen' ] );
        add_shortcode( 'unpatti_prestasi', [ $this, 'prestasi' ] );
        add_shortcode( 'unpatti_kerjasama', [ $this, 'kerjasama' ] );
        add_shortcode( 'unpatti_fasilitas', [ $this, 'fasilitas' ] );
        add_shortcode( 'unpatti_mitra_industri', [ $this, 'mitra_industri' ] );
        add_shortcode( 'unpatti_mata_kuliah', [ $this, 'mata_kuliah' ] );
        add_shortcode( 'unpatti_publikasi', [ $this, 'publikasi' ] );
        add_shortcode( 'unpatti_beasiswa', [ $this, 'beasiswa' ] );
        add_shortcode( 'unpatti_testimonial', [ $this, 'testimonial' ] );
        add_shortcode( 'unpatti_video', [ $this, 'video' ] );
        add_shortcode( 'unpatti_organisasi_mhs', [ $this, 'organisasi_mhs' ] );

        // Enqueue styles
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            'unpatti-shortcodes',
            UNPATTI_CORE_URL . 'assets/css/shortcodes.css',
            [],
            UNPATTI_CORE_VERSION
        );
    }

    /**
     * Tenaga Pendidik / Dosen Grid
     * Meta: _tenaga_pendidik_nidn, _tenaga_pendidik_jabatan_fungsional, _tenaga_pendidik_bidang_keahlian, _tenaga_pendidik_email_dosen, _tenaga_pendidik_link_profil
     */
    public function tenaga_pendidik( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => -1,
            'columns' => 3,
            'orderby' => 'menu_order',
            'order'   => 'ASC',
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'tenaga_pendidik',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada data tenaga pendidik.', 'unpatti-academic' ) . '</p>';
        }

        $jabatan_labels = [
            'guru_besar'    => 'Guru Besar',
            'lektor_kepala' => 'Lektor Kepala',
            'lektor'        => 'Lektor',
            'asisten_ahli'  => 'Asisten Ahli',
        ];

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-tenaga-pendidik">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $nidn = get_post_meta( $id, '_tenaga_pendidik_nidn', true );
            $jabatan_key = get_post_meta( $id, '_tenaga_pendidik_jabatan_fungsional', true );
            $jabatan = $jabatan_labels[ $jabatan_key ] ?? $jabatan_key;
            $bidang = get_post_meta( $id, '_tenaga_pendidik_bidang_keahlian', true );
            $email = get_post_meta( $id, '_tenaga_pendidik_email_dosen', true );
            $link = get_post_meta( $id, '_tenaga_pendidik_link_profil', true );
            $foto = get_the_post_thumbnail_url( $id, 'medium' );

            echo '<div class="unpatti-card unpatti-dosen-card">';
            echo '<div class="unpatti-card-image">';
            if ( $foto ) {
                echo '<img src="' . esc_url( $foto ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            } else {
                echo '<div class="unpatti-placeholder-image"><span class="dashicons dashicons-admin-users"></span></div>';
            }
            echo '</div>';
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title">' . esc_html( get_the_title() ) . '</h4>';
            if ( $jabatan ) {
                echo '<p class="unpatti-dosen-jabatan">' . esc_html( $jabatan ) . '</p>';
            }
            if ( $bidang ) {
                echo '<p class="unpatti-dosen-bidang">Bidang: ' . esc_html( $bidang ) . '</p>';
            }
            if ( $nidn ) {
                echo '<p class="unpatti-dosen-nidn">NIDN: ' . esc_html( $nidn ) . '</p>';
            }
            if ( $email ) {
                echo '<p class="unpatti-dosen-email"><a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></p>';
            }
            if ( $link ) {
                echo '<p class="unpatti-dosen-link"><a href="' . esc_url( $link ) . '" target="_blank">Lihat Profil</a></p>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Pimpinan Grid
     * Meta: _pimpinan_nip, _pimpinan_jabatan, _pimpinan_email, _pimpinan_periode, _pimpinan_bio, _pimpinan_urutan
     */
    public function pimpinan( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => -1,
            'columns' => 4,
            'orderby' => 'meta_value_num',
            'order'   => 'ASC',
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'pimpinan',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => $atts['orderby'],
            'meta_key'       => '_pimpinan_urutan',
            'order'          => $atts['order'],
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada data pimpinan.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-pimpinan">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $jabatan = get_post_meta( $id, '_pimpinan_jabatan', true );
            $nip = get_post_meta( $id, '_pimpinan_nip', true );
            $periode = get_post_meta( $id, '_pimpinan_periode', true );
            $foto = get_the_post_thumbnail_url( $id, 'medium' );

            echo '<div class="unpatti-card unpatti-pimpinan-card">';
            echo '<div class="unpatti-card-image">';
            if ( $foto ) {
                echo '<img src="' . esc_url( $foto ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            } else {
                echo '<div class="unpatti-placeholder-image"><span class="dashicons dashicons-businessman"></span></div>';
            }
            echo '</div>';
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title">' . esc_html( get_the_title() ) . '</h4>';
            if ( $jabatan ) {
                echo '<p class="unpatti-pimpinan-jabatan">' . esc_html( $jabatan ) . '</p>';
            }
            if ( $periode ) {
                echo '<p class="unpatti-pimpinan-periode">Periode: ' . esc_html( $periode ) . '</p>';
            }
            if ( $nip ) {
                echo '<p class="unpatti-pimpinan-nip">NIP: ' . esc_html( $nip ) . '</p>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Sambutan Ketua Program Studi (from Settings)
     * Displays leader data from unpatti_pimpinan_settings option
     * Usage: [unpatti_sambutan_kaprodi]
     * Attributes: style (full/compact), show_button (yes/no), button_text, button_url
     */
    public function sambutan_kaprodi( $atts ) {
        $atts = shortcode_atts( [
            'style'       => 'full',       // full, compact
            'show_button' => 'yes',
            'button_text' => 'Selengkapnya',
            'button_url'  => '',
            'excerpt_length' => 50,
        ], $atts );

        // Get pimpinan data from settings
        $pimpinan = get_option( 'unpatti_pimpinan_settings', [] );

        if ( empty( $pimpinan['nama'] ) ) {
            return '<p class="unpatti-empty">' . __( 'Data pimpinan belum dikonfigurasi.', 'unpatti-academic' ) . '</p>';
        }

        // Build full name with titles
        $nama_parts = [];
        if ( ! empty( $pimpinan['gelar_depan'] ) ) {
            $nama_parts[] = $pimpinan['gelar_depan'];
        }
        $nama_parts[] = $pimpinan['nama'];
        if ( ! empty( $pimpinan['gelar_belakang'] ) ) {
            $nama_parts[] = $pimpinan['gelar_belakang'];
        }
        $nama = implode( ' ', $nama_parts );

        $jabatan = ! empty( $pimpinan['jabatan'] ) ? $pimpinan['jabatan'] : '';
        $foto_id = ! empty( $pimpinan['foto_id'] ) ? (int) $pimpinan['foto_id'] : 0;
        $foto_url = $foto_id ? wp_get_attachment_image_url( $foto_id, 'medium_large' ) : '';
        $sambutan = ! empty( $pimpinan['sambutan'] ) ? $pimpinan['sambutan'] : '';

        // Auto-detect button URL if not provided
        if ( empty( $atts['button_url'] ) ) {
            $sambutan_page = get_page_by_path( 'sambutan' );
            if ( $sambutan_page ) {
                $atts['button_url'] = get_permalink( $sambutan_page->ID );
            }
        }

        // Strip HTML and get excerpt from sambutan
        $sambutan_text = wp_strip_all_tags( $sambutan );
        $sambutan_excerpt = wp_trim_words( $sambutan_text, (int) $atts['excerpt_length'], '...' );

        ob_start();

        if ( $atts['style'] === 'compact' ) {
            // Compact style - just name, jabatan, and short text
            echo '<div class="unpatti-kaprodi unpatti-kaprodi-compact">';
            echo '<div class="unpatti-kaprodi-info">';
            echo '<h4 class="unpatti-kaprodi-nama">' . esc_html( $nama ) . '</h4>';
            if ( $jabatan ) {
                echo '<p class="unpatti-kaprodi-jabatan">' . esc_html( $jabatan ) . '</p>';
            }
            echo '</div>';
            echo '</div>';
        } else {
            // Full style - with photo, name, jabatan, sambutan excerpt
            echo '<div class="unpatti-kaprodi unpatti-kaprodi-full">';

            // Photo column
            echo '<div class="unpatti-kaprodi-foto">';
            if ( $foto_url ) {
                echo '<img src="' . esc_url( $foto_url ) . '" alt="' . esc_attr( $nama ) . '">';
            } else {
                echo '<div class="unpatti-kaprodi-placeholder"><span class="dashicons dashicons-admin-users"></span></div>';
            }
            echo '</div>';

            // Content column
            echo '<div class="unpatti-kaprodi-content">';
            echo '<h3 class="unpatti-kaprodi-title">' . esc_html__( 'Sambutan Ketua Program Studi', 'unpatti-academic' ) . '</h3>';
            echo '<h4 class="unpatti-kaprodi-nama">' . esc_html( $nama ) . '</h4>';
            if ( $jabatan ) {
                echo '<p class="unpatti-kaprodi-jabatan">' . esc_html( $jabatan ) . '</p>';
            }
            if ( $sambutan_excerpt ) {
                echo '<div class="unpatti-kaprodi-text">' . esc_html( $sambutan_excerpt ) . '</div>';
            }
            if ( $atts['show_button'] === 'yes' && $atts['button_url'] ) {
                echo '<a href="' . esc_url( $atts['button_url'] ) . '" class="unpatti-btn unpatti-btn-primary">' . esc_html( $atts['button_text'] ) . '</a>';
            }
            echo '</div>';

            echo '</div>';
        }

        return ob_get_clean();
    }

    /**
     * Agenda / Events
     * Meta: _agenda_tanggal_mulai_agenda, _agenda_tanggal_akhir_agenda, _agenda_lokasi_agenda, _agenda_poster_agenda
     */
    public function agenda( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => 5,
            'style'   => 'list',
            'columns' => 3,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'agenda',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'meta_value',
            'meta_key'       => '_agenda_tanggal_mulai_agenda',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada agenda.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();

        if ( $atts['style'] === 'grid' ) {
            echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-agenda">';
        } else {
            echo '<div class="unpatti-list unpatti-agenda">';
        }

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $tanggal = get_post_meta( $id, '_agenda_tanggal_mulai_agenda', true );
            $tanggal_akhir = get_post_meta( $id, '_agenda_tanggal_akhir_agenda', true );
            $lokasi = get_post_meta( $id, '_agenda_lokasi_agenda', true );

            echo '<div class="unpatti-card unpatti-agenda-card">';
            if ( $tanggal ) {
                echo '<div class="unpatti-agenda-date">';
                echo '<span class="day">' . esc_html( date( 'd', strtotime( $tanggal ) ) ) . '</span>';
                echo '<span class="month">' . esc_html( date_i18n( 'M', strtotime( $tanggal ) ) ) . '</span>';
                echo '</div>';
            }
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h4>';
            if ( $lokasi ) {
                echo '<p class="unpatti-agenda-lokasi"><span class="dashicons dashicons-location"></span> ' . esc_html( $lokasi ) . '</p>';
            }
            if ( has_excerpt() || get_the_content() ) {
                echo '<p class="unpatti-excerpt">' . esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 15 ) ) . '</p>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Pengumuman / Announcements
     * Meta: _pengumuman_tanggal_berlaku, _pengumuman_file_lampiran
     */
    public function pengumuman( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => 5,
            'style'   => 'list',
            'columns' => 3,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'pengumuman',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada pengumuman.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<div class="unpatti-list unpatti-pengumuman">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $lampiran_id = get_post_meta( $id, '_pengumuman_file_lampiran', true );
            $lampiran_url = $lampiran_id ? wp_get_attachment_url( (int) $lampiran_id ) : '';

            echo '<div class="unpatti-card unpatti-pengumuman-card">';
            echo '<div class="unpatti-card-content">';
            echo '<div class="unpatti-pengumuman-meta">';
            echo '<span class="unpatti-date">' . esc_html( get_the_date( 'd M Y' ) ) . '</span>';
            echo '</div>';
            echo '<h4 class="unpatti-card-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h4>';
            if ( has_excerpt() || get_the_content() ) {
                echo '<p class="unpatti-excerpt">' . esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) ) . '</p>';
            }
            if ( $lampiran_url ) {
                echo '<p><a href="' . esc_url( $lampiran_url ) . '" class="unpatti-btn-sm" target="_blank"><span class="dashicons dashicons-download"></span> Unduh Lampiran</a></p>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * FAQ Accordion
     * Meta: _faq_urutan_faq, _faq_kategori_faq (uses editor for answer)
     */
    public function faq( $atts ) {
        $atts = shortcode_atts( [
            'limit' => -1,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'faq',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'meta_value_num',
            'meta_key'       => '_faq_urutan_faq',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada FAQ.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<div class="unpatti-faq-accordion">';

        $i = 0;
        while ( $query->have_posts() ) {
            $query->the_post();

            echo '<div class="unpatti-faq-item' . ( $i === 0 ? ' active' : '' ) . '">';
            echo '<button class="unpatti-faq-question" aria-expanded="' . ( $i === 0 ? 'true' : 'false' ) . '">';
            echo '<span>' . esc_html( get_the_title() ) . '</span>';
            echo '<span class="unpatti-faq-icon">+</span>';
            echo '</button>';
            echo '<div class="unpatti-faq-answer"' . ( $i === 0 ? ' style="display:block;"' : '' ) . '>';
            echo '<div class="unpatti-faq-content">' . wp_kses_post( get_the_content() ) . '</div>';
            echo '</div>';
            echo '</div>';
            $i++;
        }

        echo '</div>';
        wp_reset_postdata();

        // Inline JS for accordion
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".unpatti-faq-question").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    var item = this.parentElement;
                    var wasActive = item.classList.contains("active");
                    document.querySelectorAll(".unpatti-faq-item").forEach(function(el) {
                        el.classList.remove("active");
                        el.querySelector(".unpatti-faq-answer").style.display = "none";
                        el.querySelector(".unpatti-faq-question").setAttribute("aria-expanded", "false");
                    });
                    if (!wasActive) {
                        item.classList.add("active");
                        item.querySelector(".unpatti-faq-answer").style.display = "block";
                        this.setAttribute("aria-expanded", "true");
                    }
                });
            });
        });
        </script>';

        return ob_get_clean();
    }

    /**
     * Galeri Grid
     * Meta: galeri_kategori_galeri, galeri_tanggal_galeri (uses featured image)
     */
    public function galeri( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => 12,
            'columns' => 4,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'galeri',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada galeri.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-galeri">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $foto = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            $thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium' );

            if ( $thumb ) {
                echo '<a href="' . esc_url( $foto ?: $thumb ) . '" class="unpatti-galeri-item" data-lightbox="galeri">';
                echo '<img src="' . esc_url( $thumb ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                echo '<div class="unpatti-galeri-overlay"><span>' . esc_html( get_the_title() ) . '</span></div>';
                echo '</a>';
            }
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Dokumen List
     * Meta: _dokumen_file_dokumen, _dokumen_kategori_dokumen, _dokumen_tahun_dokumen, _dokumen_sumber_dokumen
     */
    public function dokumen( $atts ) {
        $atts = shortcode_atts( [
            'limit'    => -1,
            'kategori' => '',
        ], $atts );

        $args = [
            'post_type'      => 'dokumen',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ];

        if ( $atts['kategori'] ) {
            $args['meta_query'] = [
                [
                    'key'   => '_dokumen_kategori_dokumen',
                    'value' => $atts['kategori'],
                ],
            ];
        }

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada dokumen.', 'unpatti-academic' ) . '</p>';
        }

        $kategori_labels = [
            'peraturan'  => 'Peraturan Akademik',
            'kalender'   => 'Kalender Akademik',
            'kurikulum'  => 'Kurikulum',
            'sop'        => 'SOP',
            'mutu'       => 'Dokumen Mutu',
            'akreditasi' => 'Akreditasi',
        ];

        $sumber_labels = [
            'universitas' => 'Universitas',
            'fakultas'    => 'Fakultas',
            'jurusan'     => 'Jurusan',
        ];

        ob_start();
        echo '<div class="unpatti-list unpatti-dokumen">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $file_id = get_post_meta( $id, '_dokumen_file_dokumen', true );
            $file_url = $file_id ? wp_get_attachment_url( (int) $file_id ) : '';
            $kategori_key = get_post_meta( $id, '_dokumen_kategori_dokumen', true );
            $kategori = $kategori_labels[ $kategori_key ] ?? $kategori_key;
            $tahun = get_post_meta( $id, '_dokumen_tahun_dokumen', true );
            $sumber_key = get_post_meta( $id, '_dokumen_sumber_dokumen', true );
            $sumber = $sumber_labels[ $sumber_key ] ?? $sumber_key;

            echo '<div class="unpatti-card unpatti-dokumen-card">';
            echo '<div class="unpatti-dokumen-icon"><span class="dashicons dashicons-media-document"></span></div>';
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title">' . esc_html( get_the_title() ) . '</h4>';
            if ( $kategori ) {
                echo '<p class="unpatti-dokumen-kategori">' . esc_html( $kategori ) . '</p>';
            }
            if ( $tahun ) {
                echo '<p class="unpatti-date">Tahun: ' . esc_html( $tahun ) . '</p>';
            }
            if ( $sumber ) {
                echo '<p class="unpatti-dokumen-sumber">Sumber: ' . esc_html( $sumber ) . '</p>';
            }
            echo '</div>';
            if ( $file_url ) {
                echo '<a href="' . esc_url( $file_url ) . '" class="unpatti-btn-download" target="_blank"><span class="dashicons dashicons-download"></span></a>';
            }
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Prestasi List
     * Meta: _prestasi_tanggal_prestasi, _prestasi_kategori_prestasi, _prestasi_tingkat_prestasi, _prestasi_nama_peraih
     */
    public function prestasi( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => -1,
            'columns' => 3,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'prestasi',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'meta_value',
            'meta_key'       => '_prestasi_tanggal_prestasi',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada prestasi.', 'unpatti-academic' ) . '</p>';
        }

        $tingkat_labels = [
            'lokal'         => 'Lokal',
            'nasional'      => 'Nasional',
            'internasional' => 'Internasional',
        ];

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-prestasi">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $tingkat_key = get_post_meta( $id, '_prestasi_tingkat_prestasi', true );
            $tingkat = $tingkat_labels[ $tingkat_key ] ?? $tingkat_key;
            $tanggal = get_post_meta( $id, '_prestasi_tanggal_prestasi', true );
            $peraih = get_post_meta( $id, '_prestasi_nama_peraih', true );
            $foto = get_the_post_thumbnail_url( $id, 'medium' );

            echo '<div class="unpatti-card unpatti-prestasi-card">';
            if ( $foto ) {
                echo '<div class="unpatti-card-image"><img src="' . esc_url( $foto ) . '" alt="' . esc_attr( get_the_title() ) . '"></div>';
            }
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title">' . esc_html( get_the_title() ) . '</h4>';
            if ( $peraih ) {
                echo '<p class="unpatti-prestasi-peraih">' . esc_html( $peraih ) . '</p>';
            }
            if ( $tingkat || $tanggal ) {
                echo '<p class="unpatti-prestasi-meta">';
                if ( $tingkat ) echo '<span class="tingkat">' . esc_html( $tingkat ) . '</span>';
                if ( $tanggal ) echo '<span class="tahun">' . esc_html( date_i18n( 'Y', strtotime( $tanggal ) ) ) . '</span>';
                echo '</p>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Kerjasama / Partnerships
     * Meta: _kerjasama_logo_mitra, _kerjasama_jenis_kerjasama, _kerjasama_tanggal_mulai, _kerjasama_tanggal_akhir, _kerjasama_dokumen_mou
     */
    public function kerjasama( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => -1,
            'columns' => 3,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'kerjasama',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada data kerjasama.', 'unpatti-academic' ) . '</p>';
        }

        $jenis_labels = [
            'dn' => 'Dalam Negeri',
            'ln' => 'Luar Negeri',
        ];

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-kerjasama">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $jenis_key = get_post_meta( $id, '_kerjasama_jenis_kerjasama', true );
            $jenis = $jenis_labels[ $jenis_key ] ?? $jenis_key;
            $tgl_mulai = get_post_meta( $id, '_kerjasama_tanggal_mulai', true );
            $tgl_akhir = get_post_meta( $id, '_kerjasama_tanggal_akhir', true );
            $logo = get_the_post_thumbnail_url( $id, 'medium' );

            // Build periode string
            $periode = '';
            if ( $tgl_mulai && $tgl_akhir ) {
                $periode = date_i18n( 'Y', strtotime( $tgl_mulai ) ) . ' - ' . date_i18n( 'Y', strtotime( $tgl_akhir ) );
            }

            echo '<div class="unpatti-card unpatti-kerjasama-card">';
            if ( $logo ) {
                echo '<div class="unpatti-card-logo"><img src="' . esc_url( $logo ) . '" alt="' . esc_attr( get_the_title() ) . '"></div>';
            }
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title">' . esc_html( get_the_title() ) . '</h4>';
            if ( $jenis ) {
                echo '<p class="unpatti-kerjasama-jenis">' . esc_html( $jenis ) . '</p>';
            }
            if ( $periode ) {
                echo '<p class="unpatti-kerjasama-periode">' . esc_html( $periode ) . '</p>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Fasilitas Grid
     * Meta: _fasilitas_kapasitas, _fasilitas_lokasi
     */
    public function fasilitas( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => -1,
            'columns' => 3,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'fasilitas',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada data fasilitas.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-fasilitas">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $lokasi = get_post_meta( $id, '_fasilitas_lokasi', true );
            $kapasitas = get_post_meta( $id, '_fasilitas_kapasitas', true );
            $foto = get_the_post_thumbnail_url( $id, 'large' );

            echo '<div class="unpatti-card unpatti-fasilitas-card">';
            if ( $foto ) {
                echo '<div class="unpatti-card-image"><img src="' . esc_url( $foto ) . '" alt="' . esc_attr( get_the_title() ) . '"></div>';
            }
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title">' . esc_html( get_the_title() ) . '</h4>';
            if ( has_excerpt() || get_the_content() ) {
                echo '<p class="unpatti-fasilitas-desc">' . esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 15 ) ) . '</p>';
            }
            if ( $lokasi ) {
                echo '<p class="unpatti-fasilitas-lokasi"><span class="dashicons dashicons-location"></span> ' . esc_html( $lokasi ) . '</p>';
            }
            if ( $kapasitas ) {
                echo '<p class="unpatti-fasilitas-kapasitas"><span class="dashicons dashicons-groups"></span> ' . esc_html( $kapasitas ) . '</p>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Mitra Industri Logo Grid
     * Meta: mitra_industri_logo_mitra_di, mitra_industri_jenis_kerjasama_di
     */
    public function mitra_industri( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => -1,
            'columns' => 5,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'mitra_industri',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada mitra industri.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-mitra">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $logo = get_the_post_thumbnail_url( $id, 'medium' );

            echo '<div class="unpatti-mitra-card">';
            if ( $logo ) {
                echo '<img src="' . esc_url( $logo ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            } else {
                echo '<span class="unpatti-mitra-name">' . esc_html( get_the_title() ) . '</span>';
            }
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Mata Kuliah Table
     * Meta: _mata_kuliah_kode_mk, _mata_kuliah_sks, _mata_kuliah_semester, _mata_kuliah_konsentrasi, _mata_kuliah_link_rps, _mata_kuliah_link_silabus
     */
    public function mata_kuliah( $atts ) {
        $atts = shortcode_atts( [
            'limit'    => -1,
            'semester' => '',
        ], $atts );

        $args = [
            'post_type'      => 'mata_kuliah',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'meta_value_num',
            'meta_key'       => '_mata_kuliah_semester',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ];

        if ( $atts['semester'] ) {
            $args['meta_query'] = [
                [
                    'key'   => '_mata_kuliah_semester',
                    'value' => $atts['semester'],
                ],
            ];
        }

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada data mata kuliah.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<table class="unpatti-table unpatti-mata-kuliah">';
        echo '<thead><tr>';
        echo '<th>Kode</th>';
        echo '<th>Mata Kuliah</th>';
        echo '<th>SKS</th>';
        echo '<th>Semester</th>';
        echo '</tr></thead>';
        echo '<tbody>';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $kode = get_post_meta( $id, '_mata_kuliah_kode_mk', true );
            $sks = get_post_meta( $id, '_mata_kuliah_sks', true );
            $semester = get_post_meta( $id, '_mata_kuliah_semester', true );

            echo '<tr>';
            echo '<td>' . esc_html( $kode ) . '</td>';
            echo '<td>' . esc_html( get_the_title() ) . '</td>';
            echo '<td>' . esc_html( $sks ) . '</td>';
            echo '<td>' . esc_html( $semester ) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Publikasi List
     * Meta: _publikasi_penulis_pub, _publikasi_jenis_pub, _publikasi_tahun_pub, _publikasi_link_pub, _publikasi_doi_pub, _publikasi_kategori_pub
     */
    public function publikasi( $atts ) {
        $atts = shortcode_atts( [
            'limit' => 10,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'publikasi',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'meta_value',
            'meta_key'       => '_publikasi_tahun_pub',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada publikasi.', 'unpatti-academic' ) . '</p>';
        }

        $jenis_labels = [
            'jurnal'    => 'Jurnal',
            'prosiding' => 'Prosiding',
            'buku'      => 'Buku',
            'hki'       => 'HKI',
        ];

        ob_start();
        echo '<div class="unpatti-list unpatti-publikasi">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $penulis = get_post_meta( $id, '_publikasi_penulis_pub', true );
            $jenis_key = get_post_meta( $id, '_publikasi_jenis_pub', true );
            $jenis = $jenis_labels[ $jenis_key ] ?? $jenis_key;
            $tahun = get_post_meta( $id, '_publikasi_tahun_pub', true );
            $link = get_post_meta( $id, '_publikasi_link_pub', true );
            $doi = get_post_meta( $id, '_publikasi_doi_pub', true );

            echo '<div class="unpatti-card unpatti-publikasi-card">';
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title">' . esc_html( get_the_title() ) . '</h4>';
            if ( $penulis ) {
                echo '<p class="unpatti-publikasi-penulis">' . esc_html( $penulis ) . '</p>';
            }
            echo '<p class="unpatti-publikasi-meta">';
            if ( $jenis ) echo '<span class="jurnal">' . esc_html( $jenis ) . '</span>';
            if ( $tahun ) echo '<span class="tahun">(' . esc_html( $tahun ) . ')</span>';
            echo '</p>';
            if ( $doi ) {
                echo '<p class="unpatti-publikasi-doi">DOI: ' . esc_html( $doi ) . '</p>';
            }
            if ( $link ) {
                echo '<p><a href="' . esc_url( $link ) . '" class="unpatti-btn-sm" target="_blank">Lihat Publikasi</a></p>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Beasiswa List
     * Meta: _beasiswa_persyaratan_beasiswa, _beasiswa_deadline_beasiswa, _beasiswa_link_pendaftaran
     */
    public function beasiswa( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => -1,
            'columns' => 2,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'beasiswa',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada informasi beasiswa.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-beasiswa">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $deadline = get_post_meta( $id, '_beasiswa_deadline_beasiswa', true );
            $link = get_post_meta( $id, '_beasiswa_link_pendaftaran', true );

            echo '<div class="unpatti-card unpatti-beasiswa-card">';
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title">' . esc_html( get_the_title() ) . '</h4>';
            if ( $deadline ) {
                echo '<p class="unpatti-beasiswa-deadline"><span class="dashicons dashicons-calendar-alt"></span> Deadline: ' . esc_html( date_i18n( 'd M Y', strtotime( $deadline ) ) ) . '</p>';
            }
            if ( has_excerpt() || get_the_content() ) {
                echo '<p class="unpatti-excerpt">' . esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) ) . '</p>';
            }
            if ( $link ) {
                echo '<p><a href="' . esc_url( $link ) . '" class="unpatti-btn-sm" target="_blank">Daftar Sekarang</a></p>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Testimonial Grid
     * Meta: _testimonial_jabatan, _testimonial_instansi, _testimonial_rating
     */
    public function testimonial( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => 6,
            'columns' => 3,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'testimonial',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada testimonial.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-testimonial">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $jabatan = get_post_meta( $id, '_testimonial_jabatan', true );
            $instansi = get_post_meta( $id, '_testimonial_instansi', true );
            $foto = get_the_post_thumbnail_url( $id, 'thumbnail' );

            echo '<div class="unpatti-card unpatti-testimonial-card">';
            echo '<div class="unpatti-testimonial-content">';
            echo '<p class="unpatti-testimonial-text">"' . esc_html( wp_trim_words( get_the_content(), 40 ) ) . '"</p>';
            echo '</div>';
            echo '<div class="unpatti-testimonial-author">';
            if ( $foto ) {
                echo '<img src="' . esc_url( $foto ) . '" alt="' . esc_attr( get_the_title() ) . '" class="unpatti-testimonial-photo">';
            }
            echo '<div class="unpatti-testimonial-info">';
            echo '<strong>' . esc_html( get_the_title() ) . '</strong>';
            if ( $jabatan || $instansi ) {
                echo '<span>';
                echo $jabatan ? esc_html( $jabatan ) : '';
                echo ( $jabatan && $instansi ) ? ' - ' : '';
                echo $instansi ? esc_html( $instansi ) : '';
                echo '</span>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Video Grid
     * Meta: _video_youtube_url, _video_video_duration
     */
    public function video( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => 6,
            'columns' => 3,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'video',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada video.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-video">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $url = get_post_meta( $id, '_video_youtube_url', true );
            $thumb = get_the_post_thumbnail_url( $id, 'medium' );

            // Extract YouTube ID for thumbnail
            $video_id = '';
            if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches ) ) {
                $video_id = $matches[1];
                if ( ! $thumb ) {
                    $thumb = 'https://img.youtube.com/vi/' . $video_id . '/mqdefault.jpg';
                }
            }

            echo '<div class="unpatti-card unpatti-video-card">';
            echo '<div class="unpatti-video-thumb">';
            if ( $thumb ) {
                echo '<img src="' . esc_url( $thumb ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            }
            if ( $url ) {
                echo '<a href="' . esc_url( $url ) . '" class="unpatti-video-play" target="_blank"><span class="dashicons dashicons-controls-play"></span></a>';
            }
            echo '</div>';
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title">' . esc_html( get_the_title() ) . '</h4>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Organisasi Mahasiswa Grid
     * Meta: organisasi_mhs_logo_org, organisasi_mhs_struktur_org, organisasi_mhs_tupoksi, organisasi_mhs_program_kerja
     */
    public function organisasi_mhs( $atts ) {
        $atts = shortcode_atts( [
            'limit'   => -1,
            'columns' => 3,
        ], $atts );

        $query = new \WP_Query( [
            'post_type'      => 'organisasi_mhs',
            'posts_per_page' => (int) $atts['limit'],
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            return '<p class="unpatti-empty">' . __( 'Belum ada data organisasi mahasiswa.', 'unpatti-academic' ) . '</p>';
        }

        ob_start();
        echo '<div class="unpatti-grid unpatti-grid-' . esc_attr( $atts['columns'] ) . ' unpatti-organisasi-mhs">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $logo = get_the_post_thumbnail_url( $id, 'medium' );

            echo '<div class="unpatti-card unpatti-org-card">';
            if ( $logo ) {
                echo '<div class="unpatti-card-logo"><img src="' . esc_url( $logo ) . '" alt="' . esc_attr( get_the_title() ) . '"></div>';
            }
            echo '<div class="unpatti-card-content">';
            echo '<h4 class="unpatti-card-title">' . esc_html( get_the_title() ) . '</h4>';
            if ( has_excerpt() || get_the_content() ) {
                echo '<p class="unpatti-excerpt">' . esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) ) . '</p>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }
}
