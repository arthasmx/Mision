<?php
class Module_Core_Repository_Model_Form extends Core_Model_Repository_Model {

  private $error_messages = array();

  function get_error_messages($form=null){
    if( !is_object($form) || empty($form) ){
      return null;
    }

    foreach($form->getMessages() AS $form_field => $form_value){
      $this->get_message( $form_field, $form_value );
    }
    return $this->error_messages;
  }

  function get_message($form_field=null, $field_value=null){
    if( empty($form_field) ){
      return null;
    }

    $msg = '<strong>' . App::xlat('form_element_' . $form_field) . '</strong>';
    if( count($field_value) > 1 ){
      $msg .= App::xlat('invalid_field');
    }else{
      $msg .= current( $field_value );
    }
    $this->error_messages[] = $msg;
  }


  function remove_decorator_from_all_fields($form = null, $decorator = null){
    if( !is_object($form) || empty($form) || empty($decorator) ){
      return null;
    }

    switch ($decorator){
      case 'Errors':
      case 'Label':
        foreach($form->getElements() as $element){
          $element->removeDecorator($decorator);
        }
      break;

      default:
        return null;
      break;
    }
    return true;
  }

}