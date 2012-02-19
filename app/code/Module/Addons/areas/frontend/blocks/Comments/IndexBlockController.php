<?php
require_once 'Core/Controller/Block.php';
class Addons_Comments_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function getCommentsAction(){
    App::header()->addScript(App::url()->get('/comments.js','js'));
    App::header()->addCode("
        <script type='text/javascript'>
          var comment_error = '". App::xlat('EXC_missing_arguments_at_adding_comments') ."';
          var reply_error = '". App::xlat('EXC_missing_arguments_at_adding_comments') ."';
        </script>
        ");

    $this->view->comments = $this->_module->getModel('Comments')
                                          ->get_comments( $this->getParam('id'), 1, $this->getParam('type') );
    $this->view->form = $this->_module->getModel('Forms/Comment')->get( $this->getParam('id'), $this->getParam('type') );
  }

}