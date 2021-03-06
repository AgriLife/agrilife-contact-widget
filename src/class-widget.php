<?php
/**
 * The file that creates a contact widget.
 *
 * @link       https://github.com/agrilife/agrilife-contact-widget/blob/master/src/class-agrilife-contact-us-widget.php
 * @since      0.1.0
 * @package    agrilife-contact-widget
 * @subpackage agrilife-contact-widget/src
 */

namespace AgriLife_Contact_Widget;

/**
 * Loads theme widgets
 *
 * @package agrilife-contact-widget
 * @since 0.1.0
 */
class Widget extends \WP_Widget {

	/**
	 * Default instance.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $default_instance = array(
		'title'   => '',
		'content' => '<div class="info icon-location">600 John Kimbrough Blvd, College Station, TX 77843</div><div><a class="info icon-phone" href="tel:979-845-4747">(979) 845-4747</a><span class="pipe"></span><a class="info icon-email" href="mailto:aglifesciences@tamu.edu">Contact Us</a></div>',
	);

	/**
	 * Construct the widget
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'                   => 'agrilife-contact-us',
			'description'                 => __( 'Contact information for this unit.' ),
			'customize_selective_refresh' => true,
		);

		$control_ops = array(
			'width'  => 400,
			'height' => 350,
		);
		parent::__construct( 'agrilife_contact_us', __( 'Contact Us (AgriLife)' ), $widget_ops, $control_ops );

	}

	/**
	 * Echoes the widget content
	 *
	 * @since 0.1.0
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		$instance = array_merge( $this->default_instance, $instance );
		$title    = $instance['title'];
		$content  = $instance['content'];

		$title = '<div class="title-wrap cell medium-12 small-4-collapse">' . $args['before_title'] . $title . $args['after_title'] . '</div>';

		$args['before_widget'] = str_replace( 'class="widget-wrap', 'class="widget-wrap', $args['before_widget'] );

		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $title );
		}
		echo '<div class="textwidget custom-html-widget">'; // The textwidget class is for theme styling compatibility.
		echo wp_kses(
			$content,
			array(
				'div'  => array(
					'class' => array(),
				),
				'br'   => true,
				'span' => array(
					'class' => array(),
				),
				'a'    => array(
					'class' => array(),
					'href'  => array(),
				),
			)
		);
		echo '</div>';
		echo wp_kses_post( $args['after_widget'] );

	}

	/**
	 * Outputs the settings update form
	 *
	 * @since 0.1.0
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->default_instance );
		$output   = '';

		// Title field.
		$output .= sprintf(
			'<p><label for="%s">%s</label><input type="text" id="%s" name="%s" class="title widefat" value="%s"/></p>',
			esc_attr( $this->get_field_id( 'title' ) ),
			esc_attr_e( 'Title:', 'agrilife-contact-widget' ),
			esc_attr( $this->get_field_id( 'title' ) ),
			$this->get_field_name( 'title' ),
			esc_attr( $instance['title'] )
		);

		// Content field.
		$output .= sprintf(
			'<p><textarea id="%s" rows="8" name="%s" class="content widefat">%s</textarea></p>',
			$this->get_field_id( 'content' ),
			$this->get_field_name( 'content' ),
			esc_textarea( $instance['content'] )
		);

		echo wp_kses(
			$output,
			array(
				'p'        => array(),
				'label'    => array(
					'for' => array(),
				),
				'input'    => array(
					'type'  => array(),
					'id'    => array(),
					'name'  => array(),
					'class' => array(),
					'value' => array(),
				),
				'textarea' => array(
					'id'    => array(),
					'rows'  => array(),
					'name'  => array(),
					'class' => array(),
				),
			)
		);

	}

	/**
	 * Updates a particular instance of a widget
	 *
	 * @since 0.1.0
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance          = array_merge( $this->default_instance, $old_instance );
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['content'] = $new_instance['content'];
		} else {
			$instance['content'] = wp_kses_post( $new_instance['content'] );
		}
		return $instance;

	}
}
