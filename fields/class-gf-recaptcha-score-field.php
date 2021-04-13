<?php

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

class Recaptcha_Score_GF_Field extends GF_Field {

	/**
	 * @var string $type The field type.
	 */
	public $type = 'recaptcha_score';

	/**
 * Defines the field title to be used in the form editor.
 *
 * @since  Unknown
 * @access public
 *
 * @used-by GFCommon::get_field_type_title()
 *
 * @return string The field title. Translatable and escaped.
 */
	public function get_form_editor_field_title() {
		return esc_attr__( 'reCaptcha Score', 'gf-google-recaptcha-3' );
	}

    public function is_conditional_logic_supported(){
		return false;
	}
	

	public function get_form_editor_button() {
	    return array(
	        'group' => 'advanced_fields',
	        'text'  => $this->get_form_editor_field_title(),
	    );
	}

	function get_form_editor_field_settings() {
		return array(
			'error_message_setting',
			'label_setting',
			'css_class_setting'
		);
	}

	public function get_entry_inputs() {
		return null;
	}

	public function get_field_input( $form, $value = '', $entry = null ) {
		$form_id         = $form['id'];
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		$value = esc_attr( $value );

		$id       = (int) $this->id;
		$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		$disabled_text = $is_form_editor ? 'disabled="disabled"' : '';

		$field_type         = 'text';
		$class_attribute    = $is_entry_detail || $is_form_editor ? '' : "class='gform_hidden recaptcha-score'";
		$required_attribute = 'aria-required="true"';
		$invalid_attribute  = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';

		$input = "<input name='input_{$id}' id='{$field_id}' type='$field_type' data-type='recaptcha-score' {$class_attribute} {$required_attribute} {$invalid_attribute} value='{$value}' {$disabled_text}/>";

		return sprintf( "<div class='ginput_container ginput_container_reCaptcha-score'>%s</div>", $input);
	}

	/**
	 * Returns the field markup; including field label, description, validation, and the form editor admin buttons.
	 *
	 * The {FIELD} placeholder will be replaced in GFFormDisplay::get_field_content with the markup returned by GF_Field::get_field_input().
	 *
	 * @param string|array $value                The field value. From default/dynamic population, $_POST, or a resumed incomplete submission.
	 * @param bool         $force_frontend_label Should the frontend label be displayed in the admin even if an admin label is configured.
	 * @param array        $form                 The Form Object currently being processed.
	 *
	 * @return string
	 */
	public function get_field_content( $value, $force_frontend_label, $form ) {

		$field_label = $this->get_field_label( $force_frontend_label, $value );

		$validation_message_id = 'validation_message_' . $form['id'] . '_' . $this->id;
		$validation_message = ( $this->failed_validation && ! empty( $this->validation_message ) ) ? sprintf( "<div id='%s' class='gfield_description validation_message' aria-live='polite'>%s</div>", $validation_message_id, $this->validation_message ) : '';

		$admin_buttons = $this->get_admin_buttons();

		$target_input_id = $this->get_first_input_id( $form );

		$for_attribute = empty( $target_input_id ) ? '' : "for='{$target_input_id}'";

		$is_form_editor  = $this->is_form_editor();
		$is_entry_detail = $this->is_entry_detail();
		$is_admin        = $is_form_editor || $is_entry_detail;

		if($is_admin){
			$field_content = sprintf( "%s<label class='%s' $for_attribute >%s</label>{FIELD}%s", $admin_buttons, esc_attr( $this->get_field_label_class() ), esc_html( $field_label ), $validation_message );
		} else {
			$field_content = sprintf( "{FIELD}%s", $validation_message );
		}

		return $field_content . '<div class="gf-recaptcha-div"></div>';
	}

}

GF_Fields::register( new Recaptcha_Score_GF_Field() );
