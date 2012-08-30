<?php
class Module_Articles_Repository_Model_Forms_BasicData extends Core_Model_Repository_Model {

  public function get() {
    require_once "Local/Form.php";
    $form = new Local_Form;
    $form->setAttribs( array( 'autocomplete' => 'off'
                              ,'enctype'     => 'application/x-www-form-urlencoded'
                              ,'id'          => 'article-basic-data') );


    // TITLE
    $form->addElement('text', 'title', array(
                      'label'         => App::xlat('FORM_title')
                      ,'required'     => true
                      ,'validators'   => array( array('stringLength', true, array(3) ) )
                      ,'onkeyup'      => "jQuery('input[name=seo]').val(string_to_seo(this.value))"
                      ,'class'        => 'required'
    ));


    // SEO TITLE
    $form->addElement('text', 'seo', array(
                      'label'        => App::xlat("FORM_seo")
                      ,'required'    => true
                      ,'validators'  => array( array('stringLength', true, array(3) ) )
                      ,'class'       => 'required'
    ));


    // DESCRIPTION
    $form->addElement('textarea', 'description', array(
                      'label' => App::xlat('FORM_description'),
                      'required'    => true,
                      'validators'  => array( array( 'stringLength', true, array(10))),
                      'description' => App::xlat('FORM_description_justification'),
                      'cols'        => 80,
                      'rows'        => 5
                      ,'class'      => 'required'
    ));




    // EVENT DATE
    $form->addElement('hidden', 'event_date', array('label' => App::xlat('FORM_event_date')) );
    $form->getElement('event_date')->getDecorator('AddHtml')->append('<dt class="label"> <label for="event_date"> </label> </dt> <dd class="field"> <div id="event_date_sel" style="width:240px"></div></dd>');


    // DATE TO PUBLISH
    $form->addElement('hidden', 'publicate_at', array('label' => App::xlat('FORM_date_publication')) );
    $form->getElement('publicate_at')->getDecorator('AddHtml')->append('<dt class="label"><div id="publicate_at_sel" style="width:240px"></div></dt>');




    // ARTICLE TYPE
    $article_category_type_id = App::module('Addons')->getConfig('core','article_category_type');
    $article_type = App::module('Addons')->getModel('Categories')->get_direct_children_for_select( $article_category_type_id );
    $form->addElement('select', 'type', array(
                      'label'          => App::xlat('FORM_type')
                      ,'multiOptions'  => $article_type
    ));


    // LANGUAGES
    $languages = App::module('Addons')->getModel('Languages')->get_languages_for_select(array('name','prefix'));
    $form->addElement('select', 'language', array(
                      'label'          => App::xlat('FORM_language')
                      ,'multiOptions'  => $languages
    ));



    $form->addDisplayGroup( array('title','seo','description'), 'info'       , array('legend' => App::xlat('FORM_FIELDSET_basic')) );
    $form->addDisplayGroup( array('event_date','publicate_at'), 'dates'      , array('legend' => App::xlat('FORM_FIELDSET_dates')) );
    $form->addDisplayGroup( array('type','language')          , 'properties' , array('legend' => App::xlat('FORM_FIELDSET_properties')) );


    $form->addElement( 'button', 'next', array( 'label' => App::xlat('page_next'), 'class' => 'save_next' )   );
    $form->getElement("next")->setDecorators(array('ViewHelper'));


    $form->addElement(  'hidden', 'category', array('value' => $article_category_type_id) );
    App::module('Core')->getModel('Form')->remove_decorator_from_all_fields($form,'Errors');
    return $form;
  }

}