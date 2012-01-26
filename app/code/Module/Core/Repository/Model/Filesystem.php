<?php
class Module_Core_Repository_Model_Filesystem extends Core_Model_Repository_Model{

  function is_found($file=false){
    if ( empty($file) ) return false;

    if ( file_exists( $file ) ){
      $this->setFile($file);
      clearstatcache();
      return true;
    }

    return false;
  }

  function getFileSize($size=false){
    if( empty($size) || $size<1) return false;
    return @$size/1024;
  }

  function get_file_details($file=null){
    return empty($file)? false : pathinfo($file);
  }

  function get_files_from_path($path,array $options=array()) {
    $path = WP . $path;
    // Revisamos las opciones para determinar includes y excludes
    $includes=array("/^pdf$/");
    $excludes=array();
    if (isset($options['include'])) $includes=(array)$options['include'];
    if (isset($options['exclude'])) $excludes=(array)$options['exclude'];
    // Abrimos el directorio
    if (!is_dir($path) || !is_readable($path) || !$dir_handle = opendir($path)) {
      $this->_module->exception("No se ha podido leer la ruta ".$path);
    }

    // Comenzamos su lectura
    $files=array();
    while($file = readdir($dir_handle)){
      // Son directorios, continuemos con otro loop
      if($file == "." || $file == ".."){
        continue;
      }
      // Revisamos si el archivo tiene coincidencias en includes o en excludes
      $en_includes=false;
      foreach($includes as $include) {
        try {
          if (preg_match($include,$file,$matches)) {
            $en_includes=true;
          };
        } catch (Exception $e) {
        };
      }
      $en_excludes=false;
      foreach($excludes as $exclude) {
        try {
          if (preg_match($exclude,$file,$matches)) {
            $en_excludes=true;
          };
        } catch (Exception $e) {
        };
      }
      // Revisamos la extension del archivo leido
      if ($en_includes && !$en_excludes) array_push($files, $file);
    }
    // Devolvemos array con archivos en la ruta
    if (sizeof($files)<1) {
      return false;
    } else {
      return $files;
    }
  }

}