<?php
class Module_Acl_Repository_Model_Acl_Forms_Login extends Core_Model_Repository_Model {

  public function get() {
    require_once "Local/Form.php";
    $form = new Local_Form;
    $form->setAttribs( array('id'=>'login-form', 'autocomplete'=>'off') );

    $form->addElement( 'text', 'user', array(
                       'label'        => App::xlat('FORM_login_user'),
                       'required'     => true,
                       'validators'   => array( array('stringLength', true, array(4))),
                       'class'        => 'field'
        ,'value'=>'robe@gmail.com'
    ));

    $form->addElement( 'password', 'password', array(
                       'label'        => App::xlat('FORM_login_pass'),
                       'required'     => true,
                       'validators'   => array( array('stringLength', true, array(4))),
                       'class'        => 'field'
    ));

    $form->addElement( 'submit', 'boton', array(
                       'label'        => App::xlat('FORM_submit'),
                       'class'        => 'basicButton'
    ));

    $form->addDisplayGroup( array( 'user','password','boton' ), 'elfieldset');
    $form->getDisplayGroup('elfieldset')->getDecorator('Group')->addClass('float-left');

    // Prevent CSRF Attacks
    require_once 'Zend/Form/Element/Hash.php';
    $token = new Zend_Form_Element_Hash('token');
    $token->setSalt(md5(uniqid(rand(), TRUE)));
    $form->addElement($token);

    return $form;
  }

}