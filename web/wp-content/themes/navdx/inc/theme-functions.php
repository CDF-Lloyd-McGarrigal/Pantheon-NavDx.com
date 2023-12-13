<?php
/**
 * I need to add data attributes in order to match content from
 * one form to another
 *
 * @return string
 */
function addAttributeToGravityFormsField($input, $field){

    if(!empty($_POST)){
        $value = $_POST['input_'.$field->id];
    }
    $value = !empty($value) ? $value : $field->defaultValue;

    if($field->type !== 'text'){
        return $input;
    }
    return sprintf('<input id="input_%d_%d" class="%s %s" data-field-name="%s" name="input_%d" type="%s" value="%s" placeholder="%s" aria-required="%s">',
                    $field->formId,
                    $field->id,
                    $field->size,
                    $field->cssClass,
                    !empty($field->inputName) ? $field->inputName : sanitize_title($field->label),
                    $field->id,
                    $field->type,
                    $value,
                    $field->placeholder,
                    $field->isRequired
    );

}
add_filter('gform_field_input', 'addAttributeToGravityFormsField', 10, 2);