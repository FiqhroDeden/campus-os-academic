<?php

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WP_Customize_Control' ) ) {
	class Garudatheme_Customizer extends WP_Customize_Control {
		protected function get_uri() {
			return get_template_directory_uri() . '/inc/src/assets';
		}

		public function enqueue() {
			/**
			 * Enqueue Customizer Styles
			 */
			wp_enqueue_style( 'garudatheme-select2-style', $this->get_uri() . '/css/select2.min.css', array(), '4.0.13', 'all' );
			wp_enqueue_style( 'garudatheme-customizer-style', $this->get_uri() . '/css/customizer.css', array(), '1.2', 'all' );

			/**
			 * Enqueue Customizer Scripts
			 */
			wp_enqueue_script( 'garudatheme-select2-scripts', $this->get_uri() . '/js/select2.min.js', array( 'jquery' ), '4.0.13', true );
			wp_enqueue_script( 'garudatheme-customizer-scripts', $this->get_uri() . '/js/customizer.js', array( 'jquery' ), '1.2', true );
		}
	}

	class Garudatheme_Font_Family_Customizer extends Garudatheme_Customizer {
		public $type = 'garudatheme_font_family';

		public $listfonts = array(
			'sans-serif' => array(
				'advent-pro' => 'Advent Pro',
				'alumni-sans' => 'Alumni Sans',
				'antonio' => 'Antonio',
				'anybody' => 'Anybody',
				'asap' => 'Asap',
				'bricolage-grotesque' => 'Bricolage Grotesque',
				'changa' => 'Changa',
				'comfortaa' => 'Comfortaa',
				'dosis' => 'Dosis',
				'gemunu-libre' => 'Gemunu Libre',
				'glory' => 'Glory',
				'heebo' => 'Heebo',
				'inter' => 'Inter',
				'karla' => 'Karla',
				'lexend' => 'Lexend',
				'merriweather-sans' => 'Merriweather Sans',
				'montserrat' => 'Montserrat',
				'mukta' => 'Mukta',
				'mulish' => 'Mulish',
				'noto-sans' => 'Noto Sans',
				'nunito' => 'Nunito',
				'nunito-sans' => 'Nunito Sans',
				'open-sans' => 'Open Sans',
				'outfit' => 'Outfit',
				'poppins' => 'Poppins',
				'raleway' => 'Raleway',
				'roboto' => 'Roboto',
				'rubik' => 'Rubik',
				'ruda' => 'Ruda',
				'saira' => 'Saira',
				'sarabun' => 'Sarabun'
			),
			'serif' => array(
				'alegreya' => 'Alegreya',
				'crimson-pro' => 'Crimson Pro',
				'enriqueta' => 'Enriqueta',
				'fraunces' => 'Fraunces',
				'gelasio' => 'Gelasio',
				'karma' => 'Karma',
				'laila' => 'Laila',
				'mirza' => 'Mirza',
				'pridi' => 'Pridi',
				'roboto-serif' => 'Roboto Serif',
				'spectral' => 'Spectral'
			)
		);

		public function render_content() {
		?>
			<div class="font-family-customize-control">
				<?php
				if ( !empty( $this->label ) ) {
				?>
					<label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
						<?php echo esc_html( $this->label ); ?>
					</label>
				<?php
				}

				if ( !empty( $this->description ) ) {
				?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php
				}
				?>
				<div class="customize-control-content">
				<select class="select-font-family" <?php $this->link(); ?>>
					<optgroup label="Sans Serif">
					<?php
					foreach ( $this->listfonts['sans-serif'] as $key => $name ) {
						printf( '<option value="%s" %s>%s</option>', esc_attr( $key . ',sans-serif' ), selected( $this->value(), $key, false ), esc_html( $name ) );
					}
					?>
					</optgroup>
					<optgroup label="Serif">
					<?php
					foreach ( $this->listfonts['serif'] as $key => $name ) {
						printf( '<option value="%s" %s>%s</option>', esc_attr( $key . ',serif' ), selected( $this->value(), $key, false ), esc_html( $name ) );
					}
					?>
					</optgroup>
				</select>
				</div>
			</div>
		<?php
		}
	}
}