<?php

class Module_Core_Repository_Model_Parser extends Core_Model_Repository_Model{

  public function string_to_seo($string) {
    $noAllowed = array("á", "é", "í", "ó", "ú", "Á","É","Í","Ó","Ú","Ñ","ñ");
    $Allowed = array("a", "e", "i", "o", "u","A","E","I","O","U","N","n" );
    $string = str_replace($noAllowed, $Allowed, $string);
    $string = strtolower($string);
    $string = rtrim(preg_replace("[^A-Za-z0-9 ]", "", $string)," ");
    $string = str_replace(" ", "-", $string);
    return $string;
  }

  public function truncate_string($string,$tamano = 150, $strip =true) {
    if ( $strip ){
      $string =strip_tags($string);
    }
    return substr($string,0,$tamano);
  }

  public function check_function_params($params=array()){
    foreach ($params AS $param){
      if( empty($param) ){
        $this->_module->exception( App::xlat('EXC_missing_arguments_at_adding_comments') );
      }
    }
    return true;
  }

}