<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Core_Repository_Model_Arrays extends Module_Core_Repository_Model_Abstract {

  function asociative_array_to_mysql_IN($array=array()){
    if ( empty($array) ) return false;

    $mysql_in_format='';
    foreach($array as $key){

    	 $r = explode(',',$key);
    	 if ( count($r)>0){
    		  for ($i=0; $i <= count($r)-1;$i++){
    			  $mysql_in_format .= "'".$r[$i]."',";
    		  }
    	 }else{
    		  $mysql_in_format .= "'".$key."',";
    	 }
    }
    return rtrim($mysql_in_format , ',');
  }

  function array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
  }

}