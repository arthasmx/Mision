<?php

class Module_Core_Repository_Model_Flashmsg extends Core_Model_Repository_Model {

    protected $_messages = array();
    protected $_namespace     = null;

    protected function load_namespace_messages() {
        $this->_messages = $this->_namespace->messages;
        $this->loadCss();
    }

    protected function save_messages_to_namespace() {
        $this->_namespace->messages=$this->_messages;
        $this->loadCss();
    }

    public function init() {
        $this->_namespace = $this->_module->getModel('Namespace')->get( get_class($this) );
        $this->load_namespace_messages();
    }

    public function error($msg) {
        $this->add('error',$msg);
    }

    public function info($msg) {
        $this->add('info',$msg);
    }

    public function warning($msg) {
        $this->add('warning',$msg);
    }

    public function success($msg) {
        $this->add('success',$msg);
    }

    public function add($type,$msg) {
        $this->_messages[$type][] = array( 'time' => time(), 'msg' => $msg );
        $this->save_messages_to_namespace();
    }

//    function flush($type=false){
    function get_flash_messages($type=false){
        $messages = array();

        if (!$type) {
            foreach ( array_keys((array)$this->_messages) as $msgType ) {
                $msgs = $this->get_flash_messages($msgType);
                if (count($msgs)>0) $messages[$msgType]=$msgs;
            }
            $this->_messages = array();
            $this->save_messages_to_namespace();

        } else {
            if (isset($this->_messages[$type])) {
                $messages=$this->_messages[$type];
                unset($this->_messages[$type]);
                $this->save_messages_to_namespace();
            }
        }

        if (is_array($messages) && count($messages)>0) {
            return $messages;
        }
        return false;
    }

    public function loadCss() {
        if (is_array($this->_messages) && count($this->_messages)>0) {

            // Añadimos los estilos de los mensajes a la cabecera
                App::header()->addLink(
                    App::skin('/css/blocks/flashmsg.css'),
                    array('rel'=>'stylesheet','type'=>'text/css')
                );

        }
    }
}