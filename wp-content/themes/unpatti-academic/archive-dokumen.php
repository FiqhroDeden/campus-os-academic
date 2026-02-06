<?php
/**
 * Archive Template: Dokumen
 */
get_header();

$kategori_labels = [
    'peraturan'  => 'Peraturan Akademik',
    'kalender'   => 'Kalender Akademik',
    'kurikulum'  => 'Kurikulum',
    'sop'        => 'SOP',
    'mutu'       => 'Dokumen Mutu',
    'akreditasi' => 'Akreditasi',
];

// Group documents by category
$all_docs = new WP_Query( array(
    'post_type'      => 'dokumen',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
) );

$docs_by_category = [];
if ( $all_docs->have_posts() ) {
    while ( $all_docs->have_posts() ) {
        $all_docs->the_post();
        $kategori = get_post_meta( get_the_ID(), '_dokumen_kategori_dokumen', true );
        if ( ! $kategori ) $kategori = 'lainnya';
        if ( ! isset( $docs_by_category[ $kategori ] ) ) {
            $docs_by_category[ $kategori ] = [];
        }
        $docs_by_category[ $kategori ][] = array(
            'id'     => get_the_ID(),
            'title'  => get_the_title(),
            'file'   => get_post_meta( get_the_ID(), '_dokumen_file_dokumen', true ),
            'tahun'  => get_post_meta( get_the_ID(), '_dokumen_tahun_dokumen', true ),
            'sumber' => get_post_meta( get_the_ID(), '_dokumen_sumber_dokumen', true ),
        );
    }
    wp_reset_postdata();
}
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
        <?php if ( ! empty( $docs_by_category ) ) : ?>
            <div class="document-list">
                <?php foreach ( $docs_by_category as $cat_key => $docs ) :
                    $cat_label = isset( $kategori_labels[ $cat_key ] ) ? $kategori_labels[ $cat_key ] : ucfirst( $cat_key );
                ?>
                    <div class="doc-category">
                        <h2><?php echo esc_html( $cat_label ); ?></h2>
                        <div class="doc-table">
                            <div class="doc-table-header">
                                <span class="doc-col-name">Nama Dokumen</span>
                                <span class="doc-col-year">Tahun</span>
                                <span class="doc-col-action">Aksi</span>
                            </div>
                            <?php foreach ( $docs as $doc ) : ?>
                                <div class="doc-table-row">
                                    <span class="doc-col-name">
                                        <span class="dashicons dashicons-media-document"></span>
                                        <?php echo esc_html( $doc['title'] ); ?>
                                    </span>
                                    <span class="doc-col-year"><?php echo $doc['tahun'] ? esc_html( $doc['tahun'] ) : '-'; ?></span>
                                    <span class="doc-col-action">
                                        <?php if ( $doc['file'] ) : ?>
                                            <a href="<?php echo esc_url( wp_get_attachment_url( $doc['file'] ) ); ?>" class="btn btn-primary btn-sm" target="_blank" download>
                                                <span class="dashicons dashicons-download"></span> Download
                                            </a>
                                        <?php else : ?>
                                            <span class="doc-no-file">-</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="no-content"><?php esc_html_e( 'Belum ada dokumen.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
