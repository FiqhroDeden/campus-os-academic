<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Skyrocket Customizer Custom Controls
 * @author Anthony Hortin <http://maddisondesigns.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 * @link https://github.com/maddisondesigns
 */

if ( class_exists( 'WP_Customize_Control' ) ) {
	class Skyrocket_Custom_Control extends WP_Customize_Control {
		protected function get_skyrocket_resource_url() {
			return get_template_directory_uri();
		}
	}

	class Skyrocket_Custom_Section extends WP_Customize_Section {
		protected function get_skyrocket_resource_url() {
			return get_template_directory_uri();
		}
	}

	class Garudatheme_FontFamily_Customize_Control extends Skyrocket_Custom_Control {
		public $type = 'font_family';

		public function enqueue() {
			wp_enqueue_script( 'skyrocket-select2-js', $this->get_skyrocket_resource_url() . '/assets/js/select2.full.min.js', array( 'jquery' ), '4.0.13', true );
			wp_enqueue_script( 'skyrocket-custom-controls-js', $this->get_skyrocket_resource_url() . '/assets/js/customizer.js', array( 'jquery' ), '1.2', true );
			wp_enqueue_style( 'skyrocket-select2-css', $this->get_skyrocket_resource_url() . '/assets/css/select2.min.css', array(), '4.0.13', 'all' );
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}

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
		<select class="select2-fontfamily" <?php $this->link(); ?>>
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

	 class Skyrocket_Image_Checkbox_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'image_checkbox';
		public function enqueue() {
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}

		public function render_content() {
		?>
			<div class="image_checkbox_control">
				<?php if( !empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
				<?php	$chkboxValues = explode( ',', esc_attr( $this->value() ) ); ?>
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-multi-image-checkbox" <?php $this->link(); ?> />
				<?php foreach ( $this->choices as $key => $value ) { ?>
					<label class="checkbox-label">
						<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( esc_attr( $key ), $chkboxValues ), 1 ); ?> class="multi-image-checkbox"/>
						<img src="<?php echo esc_attr( $value['image'] ); ?>" alt="<?php echo esc_attr( $value['name'] ); ?>" title="<?php echo esc_attr( $value['name'] ); ?>" />
					</label>
				<?php	} ?>
			</div>
		<?php
		}
	}

	 class Skyrocket_Text_Radio_Button_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'text_radio_button';
		public function enqueue() {
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}

		public function render_content() {
		?>
			<div class="text_radio_button_control">
				<?php if( !empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>

				<div class="radio-buttons">
					<?php foreach ( $this->choices as $key => $value ) { ?>
						<label class="radio-button-label">
							<input type="radio" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
							<span><?php echo esc_html( $value ); ?></span>
						</label>
					<?php	} ?>
				</div>
			</div>
		<?php
		}
	}

	class Skyrocket_Image_Radio_Button_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'image_radio_button';
		public function enqueue() {
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}

		public function render_content() {
		?>
			<div class="image_radio_button_control">
				<?php if( !empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>

				<?php foreach ( $this->choices as $key => $value ) { ?>
					<label class="radio-button-label">
						<input type="radio" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
						<img src="<?php echo esc_attr( $value['image'] ); ?>" alt="<?php echo esc_attr( $value['name'] ); ?>" title="<?php echo esc_attr( $value['name'] ); ?>" />
					</label>
				<?php	} ?>
			</div>
		<?php
		}
	}

	class Skyrocket_Single_Accordion_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'single_accordion';
		public function enqueue() {
			wp_enqueue_script( 'skyrocket-custom-controls-js', $this->get_skyrocket_resource_url() . '/assets/js/customizer.js', array( 'jquery' ), '1.2', true );
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}

		public function render_content() {
			$allowed_html = array(
				'a' => array(
					'href' => array(),
					'title' => array(),
					'class' => array(),
					'target' => array(),
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
				'i' => array(
					'class' => array()
				),
			);
		?>
			<div class="single-accordion-custom-control">
				<div class="single-accordion-toggle"><?php echo esc_html( $this->label ); ?><span class="accordion-icon-toggle dashicons dashicons-plus"></span></div>
				<div class="single-accordion customize-control-description">
					<?php
						if ( is_array( $this->description ) ) {
							echo '<ul class="single-accordion-description">';
							foreach ( $this->description as $key => $value ) {
								echo '<li>' . $key . wp_kses( $value, $allowed_html ) . '</li>';
							}
							echo '</ul>';
						}
						else {
							echo wp_kses( $this->description, $allowed_html );
						}
				  ?>
				</div>
			</div>
		<?php
		}
	}

	class Skyrocket_Simple_Notice_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'simple_notice';
		public function render_content() {
			$allowed_html = array(
				'a' => array(
					'href' => array(),
					'title' => array(),
					'class' => array(),
					'target' => array(),
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
				'i' => array(
					'class' => array()
				),
				'span' => array(
					'class' => array(),
				),
				'code' => array(),
			);
		?>
			<div class="simple-notice-custom-control">
				<?php if( !empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo wp_kses( $this->description, $allowed_html ); ?></span>
				<?php } ?>
			</div>
		<?php
		}
	}

	class Skyrocket_Slider_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'slider_control';
		public function enqueue() {
			wp_enqueue_script( 'skyrocket-custom-controls-js', $this->get_skyrocket_resource_url() . '/assets/js/customizer.js', array( 'jquery', 'jquery-ui-core' ), '1.2', true );
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}
		public function render_content() {
		?>
			<div class="slider-custom-control">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><input type="number" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-slider-value" <?php $this->link(); ?> />
				<div class="slider" slider-min-value="<?php echo esc_attr( $this->input_attrs['min'] ); ?>" slider-max-value="<?php echo esc_attr( $this->input_attrs['max'] ); ?>" slider-step-value="<?php echo esc_attr( $this->input_attrs['step'] ); ?>"></div><span class="slider-reset dashicons dashicons-image-rotate" slider-reset-value="<?php echo esc_attr( $this->value() ); ?>"></span>
			</div>
		<?php
		}
	}

	class Skyrocket_Toggle_Switch_Custom_control extends Skyrocket_Custom_Control {
		public $type = 'toggle_switch';
		public function enqueue(){
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}

		public function render_content(){
		?>
			<div class="toggle-switch-control">
				<div class="toggle-switch">
					<input type="checkbox" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" class="toggle-switch-checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?>>
					<label class="toggle-switch-label" for="<?php echo esc_attr( $this->id ); ?>">
						<span class="toggle-switch-inner"></span>
						<span class="toggle-switch-switch"></span>
					</label>
				</div>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
			</div>
		<?php
		}
	}

	class Skyrocket_Sortable_Repeater_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'sortable_repeater';
		public $button_labels = array();
		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			parent::__construct( $manager, $id, $args );
			$this->button_labels = wp_parse_args( $this->button_labels,
				array(
					'add' => __( 'Add', 'skyrocket' ),
				)
			);
		}

		public function enqueue() {
			wp_enqueue_script( 'skyrocket-custom-controls-js', $this->get_skyrocket_resource_url() . '/assets/js/customizer.js', array( 'jquery', 'jquery-ui-core' ), '1.2', true );
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}

		public function render_content() {
		?>
		  <div class="sortable_repeater_control">
				<?php if( !empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-sortable-repeater" <?php $this->link(); ?> />
				<div class="sortable_repeater sortable">
					<div class="repeater">
						<input type="text" value="" class="repeater-input" placeholder="https://" /><span class="dashicons dashicons-sort"></span><a class="customize-control-sortable-repeater-delete" href="#"><span class="dashicons dashicons-no-alt"></span></a>
					</div>
				</div>
				<button class="button customize-control-sortable-repeater-add" type="button"><?php echo $this->button_labels['add']; ?></button>
			</div>
		<?php
		}
	}

	class Skyrocket_Dropdown_Select2_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'dropdown_select2';
		private $multiselect = false;
		private $placeholder = 'Please select...';
		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			parent::__construct( $manager, $id, $args );
			if ( isset( $this->input_attrs['multiselect'] ) && $this->input_attrs['multiselect'] ) {
				$this->multiselect = true;
			}
			if ( isset( $this->input_attrs['placeholder'] ) && $this->input_attrs['placeholder'] ) {
				$this->placeholder = $this->input_attrs['placeholder'];
			}
		}

		public function enqueue() {
			wp_enqueue_script( 'skyrocket-select2-js', $this->get_skyrocket_resource_url() . '/assets/js/select2.full.min.js', array( 'jquery' ), '4.0.13', true );
			wp_enqueue_script( 'skyrocket-custom-controls-js', $this->get_skyrocket_resource_url() . '/assets/js/customizer.js', array( 'skyrocket-select2-js' ), '1.2', true );
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
			wp_enqueue_style( 'skyrocket-select2-css', $this->get_skyrocket_resource_url() . '/assets/css/select2.min.css', array(), '4.0.13', 'all' );
		}

		public function render_content() {
			$defaultValue = $this->value();
			if ( $this->multiselect ) {
				$defaultValue = explode( ',', $this->value() );
			}
		?>
			<div class="dropdown_select2_control">
				<?php if( !empty( $this->label ) ) { ?>
					<label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
						<?php echo esc_html( $this->label ); ?>
					</label>
				<?php } ?>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-dropdown-select2" value="<?php echo esc_attr( $this->value() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?> />
				<select name="select2-list-<?php echo ( $this->multiselect ? 'multi[]' : 'single' ); ?>" class="customize-control-select2" data-placeholder="<?php echo $this->placeholder; ?>" <?php echo ( $this->multiselect ? 'multiple="multiple" ' : '' ); ?>>
					<?php
						if ( !$this->multiselect ) {
							echo '<option></option>';
						}
						foreach ( $this->choices as $key => $value ) {
							if ( is_array( $value ) ) {
								echo '<optgroup label="' . esc_attr( $key ) . '">';
								foreach ( $value as $optgroupkey => $optgroupvalue ) {
									if( $this->multiselect ){
										echo '<option value="' . esc_attr( $optgroupkey ) . '" ' . ( in_array( esc_attr( $optgroupkey ), $defaultValue ) ? 'selected="selected"' : '' ) . '>' . esc_attr( $optgroupvalue ) . '</option>';
									}
									else{
										echo '<option value="' . esc_attr( $optgroupkey ) . '" ' . selected( esc_attr( $optgroupkey ), $defaultValue, false )  . '>' . esc_attr( $optgroupvalue ) . '</option>';
									}
								}
								echo '</optgroup>';
							}
							else {
								if( $this->multiselect ){
									echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( esc_attr( $key ), $defaultValue ) ? 'selected="selected"' : '' ) . '>' . esc_attr( $value ) . '</option>';
								}
								else{
									echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $key ), $defaultValue, false )  . '>' . esc_attr( $value ) . '</option>';
								}
							}
						}
					?>
				</select>
			</div>
		<?php
		}
	}

	class Skyrocket_Dropdown_Posts_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'dropdown_posts';
		private $posts = array();
		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			parent::__construct( $manager, $id, $args );
			$this->posts = get_posts( $this->input_attrs );
		}

		public function render_content() {
		?>
			<div class="dropdown_posts_control">
				<?php if( !empty( $this->label ) ) { ?>
					<label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
						<?php echo esc_html( $this->label ); ?>
					</label>
				<?php } ?>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
				<select name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" <?php $this->link(); ?>>
					<?php
						if( !empty( $this->posts ) ) {
							foreach ( $this->posts as $post ) {
								printf( '<option value="%s" %s>%s</option>',
									$post->ID,
									selected( $this->value(), $post->ID, false ),
									$post->post_title
								);
							}
						}
					?>
				</select>
			</div>
		<?php
		}
	}

	class Skyrocket_TinyMCE_Custom_control extends Skyrocket_Custom_Control {
		public $type = 'tinymce_editor';
		public function enqueue(){
			wp_enqueue_script( 'skyrocket-custom-controls-js', $this->get_skyrocket_resource_url() . '/assets/js/customizer.js', array( 'jquery' ), '1.2', true );
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
			wp_enqueue_editor();
		}

		public function to_json() {
			parent::to_json();
			$this->json['skyrockettinymcetoolbar1'] = isset( $this->input_attrs['toolbar1'] ) ? esc_attr( $this->input_attrs['toolbar1'] ) : 'bold italic bullist numlist alignleft aligncenter alignright link';
			$this->json['skyrockettinymcetoolbar2'] = isset( $this->input_attrs['toolbar2'] ) ? esc_attr( $this->input_attrs['toolbar2'] ) : '';
			$this->json['skyrocketmediabuttons'] = isset( $this->input_attrs['mediaButtons'] ) && ( $this->input_attrs['mediaButtons'] === true ) ? true : false;
		}

		public function render_content(){
		?>
			<div class="tinymce-control">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
				<textarea id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-tinymce-editor" <?php $this->link(); ?>><?php echo esc_html( $this->value() ); ?></textarea>
			</div>
		<?php
		}
	}

	class Skyrocket_Customize_Alpha_Color_Control extends Skyrocket_Custom_Control {
		public $type = 'alpha-color';
		public $palette;
		public $show_opacity;
		public function enqueue() {
			wp_enqueue_script( 'skyrocket-custom-controls-js', $this->get_skyrocket_resource_url() . '/assets/js/customizer.js', array( 'jquery', 'wp-color-picker' ), '1.2', true );
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array( 'wp-color-picker' ), '1.2', 'all' );
		}

		public function render_content() {
			if ( is_array( $this->palette ) ) {
				$palette = implode( '|', $this->palette );
			} else {
				$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
			}

			$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';

			?>
				<label>
					<?php
					if ( isset( $this->label ) && '' !== $this->label ) {
						echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>';
					}
					if ( isset( $this->description ) && '' !== $this->description ) {
						echo '<span class="description customize-control-description">' . sanitize_text_field( $this->description ) . '</span>';
					} ?>
				</label>
				<input class="alpha-color-control" type="text" data-show-opacity="<?php echo $show_opacity; ?>" data-palette="<?php echo esc_attr( $palette ); ?>" data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?>  />
			<?php
		}
	}

	class Skyrocket_Alpha_Color_Control extends Skyrocket_Custom_Control {
		public $type = 'wpcolorpicker-alpha-color';
		public $attributes = "";
		public $defaultPalette = array(
			'#000000',
			'#ffffff',
			'#dd3333',
			'#dd9933',
			'#eeee22',
			'#81d742',
			'#1e73be',
			'#8224e3',
		);

		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			parent::__construct( $manager, $id, $args );
			$this->attributes .= 'data-default-color="' . esc_attr( $this->value() ) . '"';
			$this->attributes .= 'data-alpha="true"';
			$this->attributes .= 'data-alpha-reset="' . ( isset( $this->input_attrs['resetalpha'] ) ? $this->input_attrs['resetalpha'] : 'true' ) . '"';
			$this->attributes .= 'data-alpha-custom-width="0"';
			$this->attributes .= 'data-alpha-enabled="true"';
		}

		public function enqueue() {
			wp_enqueue_script( 'wp-color-picker-alpha', $this->get_skyrocket_resource_url() . '/assets/js/wp-color-picker-alpha-min.js', array( 'wp-color-picker' ), '3.0.2', true );
			wp_enqueue_script( 'skyrocket-custom-controls-js', $this->get_skyrocket_resource_url() . '/assets/js/customizer.js', array( 'jquery', 'wp-color-picker-alpha' ), '1.2', true );
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
			wp_enqueue_style( 'wp-color-picker' );
		}

		public function to_json() {
			parent::to_json();
			$this->json['colorpickerpalette'] = isset( $this->input_attrs['palette'] ) ? $this->input_attrs['palette'] : $this->defaultPalette;
		}

		public function render_content() {
		?>
		  <div class="wpcolorpicker_alpha_color_control">
				<?php if( !empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
				<input type="text" class="wpcolorpicker-alpha-color-picker color-picker" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" <?php echo $this->attributes; ?> <?php $this->link(); ?> />
			</div>
		<?php
		}
	}

	class Skyrocket_Pill_Checkbox_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'pill_checkbox';
		private $sortable = false;
		private $fullwidth = false;
		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			parent::__construct( $manager, $id, $args );
			if ( isset( $this->input_attrs['sortable'] ) && $this->input_attrs['sortable'] ) {
				$this->sortable = true;
			}
			if ( isset( $this->input_attrs['fullwidth'] ) && $this->input_attrs['fullwidth'] ) {
				$this->fullwidth = true;
			}
		}

		public function enqueue() {
			wp_enqueue_script( 'skyrocket-custom-controls-js', $this->get_skyrocket_resource_url() . '/assets/js/customizer.js', array( 'jquery', 'jquery-ui-core' ), '1.2', true );
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}

		public function render_content() {
			$reordered_choices = array();
			$saved_choices = explode( ',', esc_attr( $this->value() ) );

			if( $this->sortable ) {
				foreach ( $saved_choices as $key => $value ) {
					if( isset( $this->choices[$value] ) ) {
						$reordered_choices[$value] = $this->choices[$value];
					}
				}
				$reordered_choices = array_merge( $reordered_choices, array_diff_assoc( $this->choices, $reordered_choices ) );
			}
			else {
				$reordered_choices = $this->choices;
			}
		?>
			<div class="pill_checkbox_control">
				<?php if( !empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-sortable-pill-checkbox" <?php $this->link(); ?> />
				<div class="sortable_pills<?php echo ( $this->sortable ? ' sortable' : '' ) . ( $this->fullwidth ? ' fullwidth_pills' : '' ); ?>">
				<?php foreach ( $reordered_choices as $key => $value ) { ?>
					<label class="checkbox-label">
						<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( esc_attr( $key ), $saved_choices, true ), true ); ?> class="sortable-pill-checkbox"/>
						<span class="sortable-pill-title"><?php echo esc_attr( $value ); ?></span>
						<?php if( $this->sortable && $this->fullwidth ) { ?>
							<span class="dashicons dashicons-sort"></span>
						<?php } ?>
					</label>
				<?php	} ?>
				</div>
			</div>
		<?php
		}
	}

	class Skyrocket_Divider_Custom_Control extends Skyrocket_Custom_Control {
		public $type = 'simple_divider';
		private $available_divider_widths = array( "default", "full", "half" );
		private $available_divider_types = array( "solid", "dashed", "dotted", "double" );
		private $dividerwidth = 'default';
		private $dividertype = 'solid';
		private $margintop = 20;
		private $marginbottom = 20;
		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			parent::__construct( $manager, $id, $args );
			if ( isset( $this->input_attrs['width'] ) ) {
				if ( in_array( strtolower( $this->input_attrs['width'] ), $this->available_divider_widths, true ) ) {
					$this->dividerwidth = strtolower( $this->input_attrs['width'] );
				}
			}
			if ( isset( $this->input_attrs['type'] ) ) {
				if ( in_array( strtolower( $this->input_attrs['type'] ), $this->available_divider_types, true ) ) {
					$this->dividertype = strtolower( $this->input_attrs['type'] );
				}
			}
			if ( isset( $this->input_attrs['margintop'] ) &&  is_numeric( $this->input_attrs['margintop'] ) ) {
				$this->margintop = abs( (int)$this->input_attrs['margintop'] );
			}
			if ( isset( $this->input_attrs['marginbottom'] ) &&  is_numeric( $this->input_attrs['marginbottom'] ) ) {
				$this->marginbottom = abs( (int)$this->input_attrs['marginbottom'] );
			}
		}
		public function enqueue() {
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}
		public function render_content() {
		?>
			<div class="simple-divider-custom-control simple-divider-type-<?php echo $this->dividertype; ?> simple-divider-width-<?php echo $this->dividerwidth; ?>" style="margin-top:<?php echo $this->margintop; ?>px;margin-bottom:<?php echo $this->marginbottom; ?>px"></div>
		<?php
		}
	}

	class Skyrocket_Upsell_Section extends Skyrocket_Custom_Section {
		public $type = 'skyrocket-upsell';
		public $url = '';
		public $backgroundcolor = '';
		public $textcolor = '';
		public function enqueue() {
			wp_enqueue_script( 'skyrocket-custom-controls-js', $this->get_skyrocket_resource_url() . '/assets/js/customizer.js', array( 'jquery' ), '1.2', true );
			wp_enqueue_style( 'skyrocket-custom-controls-css', $this->get_skyrocket_resource_url() . '/assets/css/customizer.css', array(), '1.2', 'all' );
		}

		protected function render() {
			$bkgrndcolor = !empty( $this->backgroundcolor ) ? esc_attr( $this->backgroundcolor ) : '#fff';
			$color = !empty( $this->textcolor ) ? esc_attr( $this->textcolor ) : '#555d66';
			?>
			<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="skyrocket_upsell_section accordion-section control-section control-section-<?php echo esc_attr( $this->id ); ?> cannot-expand">
				<h3 class="upsell-section-title" <?php echo ' style="color:' . $color . ';border-left-color:' . $bkgrndcolor .';border-right-color:' . $bkgrndcolor .';"'; ?>>
					<a href="<?php echo esc_url( $this->url); ?>" target="_blank"<?php echo ' style="background-color:' . $bkgrndcolor . ';color:' . $color .';"'; ?>><?php echo esc_html( $this->title ); ?></a>
				</h3>
			</li>
			<?php
		}
	}

	if ( ! function_exists( 'skyrocket_url_sanitization' ) ) {
		function skyrocket_url_sanitization( $input ) {
			if ( strpos( $input, ',' ) !== false) {
				$input = explode( ',', $input );
			}
			if ( is_array( $input ) ) {
				foreach ($input as $key => $value) {
					$input[$key] = esc_url_raw( $value );
				}
				$input = implode( ',', $input );
			}
			else {
				$input = esc_url_raw( $input );
			}
			return $input;
		}
	}

	if ( ! function_exists( 'skyrocket_switch_sanitization' ) ) {
		function skyrocket_switch_sanitization( $input ) {
			if ( true === $input ) {
				return 1;
			} else {
				return 0;
			}
		}
	}

	if ( ! function_exists( 'skyrocket_radio_sanitization' ) ) {
		function skyrocket_radio_sanitization( $input, $setting ) {
		 $choices = $setting->manager->get_control( $setting->id )->choices;

			if ( array_key_exists( $input, $choices ) ) {
				return $input;
			} else {
				return $setting->default;
			}
		}
	}

	if ( ! function_exists( 'skyrocket_sanitize_integer' ) ) {
		function skyrocket_sanitize_integer( $input ) {
			return (int) $input;
		}
	}

	if ( ! function_exists( 'skyrocket_text_sanitization' ) ) {
		function skyrocket_text_sanitization( $input ) {
			if ( strpos( $input, ',' ) !== false) {
				$input = explode( ',', $input );
			}
			if( is_array( $input ) ) {
				foreach ( $input as $key => $value ) {
					$input[$key] = sanitize_text_field( $value );
				}
				$input = implode( ',', $input );
			}
			else {
				$input = sanitize_text_field( $input );
			}
			return $input;
		}
	}

	if ( ! function_exists( 'skyrocket_array_sanitization' ) ) {
		function skyrocket_array_sanitization( $input ) {
			if( is_array( $input ) ) {
				foreach ( $input as $key => $value ) {
					$input[$key] = sanitize_text_field( $value );
				}
			}
			else {
				$input = '';
			}
			return $input;
		}
	}

	if ( ! function_exists( 'skyrocket_hex_rgba_sanitization' ) ) {
		function skyrocket_hex_rgba_sanitization( $input, $setting ) {
			if ( empty( $input ) || is_array( $input ) ) {
				return $setting->default;
			}

			if ( false === strpos( $input, 'rgb' ) ) {
				$input = sanitize_hex_color( $input );
			} else {
				if ( false === strpos( $input, 'rgba' ) ) {
					$input = str_replace( ' ', '', $input );
					sscanf( $input, 'rgb(%d,%d,%d)', $red, $green, $blue );
					$input = 'rgb(' . skyrocket_in_range( $red, 0, 255 ) . ',' . skyrocket_in_range( $green, 0, 255 ) . ',' . skyrocket_in_range( $blue, 0, 255 ) . ')';
				}
				else {
					$input = str_replace( ' ', '', $input );
					sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
					$input = 'rgba(' . skyrocket_in_range( $red, 0, 255 ) . ',' . skyrocket_in_range( $green, 0, 255 ) . ',' . skyrocket_in_range( $blue, 0, 255 ) . ',' . skyrocket_in_range( $alpha, 0, 1 ) . ')';
				}
			}
			return $input;
		}
	}

	if ( ! function_exists( 'skyrocket_in_range' ) ) {
		function skyrocket_in_range( $input, $min, $max ){
			if ( $input < $min ) {
				$input = $min;
			}
			if ( $input > $max ) {
				$input = $max;
			}
			return $input;
		}
	}

	if ( ! function_exists( 'skyrocket_date_time_sanitization' ) ) {
		function skyrocket_date_time_sanitization( $input, $setting ) {
			$datetimeformat = 'Y-m-d';
			if ( $setting->manager->get_control( $setting->id )->include_time ) {
				$datetimeformat = 'Y-m-d H:i:s';
			}
			$date = DateTime::createFromFormat( $datetimeformat, $input );
			if ( $date === false ) {
				$date = DateTime::createFromFormat( $datetimeformat, $setting->default );
			}
			return $date->format( $datetimeformat );
		}
	}

	if ( ! function_exists( 'skyrocket_range_sanitization' ) ) {
		function skyrocket_range_sanitization( $input, $setting ) {
			$attrs = $setting->manager->get_control( $setting->id )->input_attrs;

			$min = ( isset( $attrs['min'] ) ? $attrs['min'] : $input );
			$max = ( isset( $attrs['max'] ) ? $attrs['max'] : $input );
			$step = ( isset( $attrs['step'] ) ? $attrs['step'] : 1 );

			$number = floor( $input / $attrs['step'] ) * $attrs['step'];

			return skyrocket_in_range( $number, $min, $max );
		}
	}

}