<?php
class Module_Default_Repository_Model_Forms_Contact extends Core_Model_Repository_Model {

  public function get($post=false) {
    require_once "Local/Form.php";
    $form = new Local_Form;
    $form->setAttribs( array( 'autocomplete' => 'off',
                              'enctype'      => 'multipart/form-data'
                              ,'id'          => 'form-contact' ));

    $form->addElement(  'text', 'name', array(
                        'description' => App::xlat('FORM_name'),
                        'required'    => true,
                        'validators'  => array( array( 'stringLength', true, array(6))),
                        'class'       => 'required'
     ));

    $form->addElement(  'text', 'email', array(
                        'description' => App::xlat('FORM_email'),
                        'required'    => true,
                        'validators'  => array( 'EmailAddress', array( 'stringLength', true, array(6))),
                        'class'       => 'required'
     ));

    $form->addElement(  'textarea', 'comment', array(
                        'description' => App::xlat('FORM_comments'),
                        'required'    => true,
                        'validators'  => array( array( 'stringLength', true, array(10))),
                        'class'       => 'required',
                        'cols'        => 80,
                        'rows'        => 8
     ));

     $form->addElement( 'text', 'captcha', array(
                        'required'   => true,
                        'validators' => array( array('stringLength', true, array(5))),
                        'size'       => 5,
                        'maxlength'  => 5,
                        'class'      => 'required captcha'
     ));

     $form->getElement('captcha')->getDecorator('AddHtml')->prepend(App::xlat('FORM_captcha'));
     $form->getElement('captcha')
          ->getDecorator('AddHtml')
          ->prepend('<img src="'.App::base('/core/captcha/get/'.rand(0,9999)).'" class="captcha"/>' );

     $form->addElement( 'submit', 'submit', array( 'label' => App::xlat('FORM_submit') ));

    return $form;
  }
}