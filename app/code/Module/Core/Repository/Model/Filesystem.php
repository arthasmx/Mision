<?php
class Module_Core_Repository_Model_Filesystem extends Core_Model_Repository_Model{

  private $file	 = null;
  private $mimes = null;

  function init(){
    $this->mimes = $this->_module->getConfig('core', 'mime');
  }

  // File MUST exist, otherwise it'll throw an exception
  // The objective of this class is to handle existing files, that's why U have to set FULLPATH file name
  // $file MUST has a relative path ( predicaciones/folder/folder/filename.extension )
  function set_file($file = null){
    if ( empty($file) ){
      $this->_module->exception( App::xlat('EXC_file_is_not_set') );
    }

    // All uploaded files MUST be located in this folder (audio, video, files)
    $media_folder = WP .DS. App::getConfig('media_folder');
    $this->file = $media_folder .DS. $file;
    if( ! $this->is_found() ){
      $this->_module->exception( App::xlat('EXC_file_wasnt_found') );
    }

    return $this;
  }

  function get_file(){
    if ( empty($this->file) ) {
      $this->_module->exception( App::xlat('EXC_file_is_not_set') );
    }
    return $this->file;
  }

  function get_file_info(){
    $basic             = pathinfo( $this->get_file() );
    $basic['mime']     = $this->get_mime($basic['extension']);
    $advanced          = stat( $this->get_file() );
    return array_merge( $basic, array( 'size'=>$advanced['size'], 'atime'=>$advanced['atime'], 'mtime'=>$advanced['mtime'] ) );
  }

  function is_found(){
    return ( empty($this->file) || ! file_exists( $this->file ) ) ?
      false
    :
      true;
  }

  function force_to_download(){
    $file_info = $this->get_file_info();
    $file_to_download = $file_info['dirname'] .DS. $file_info['basename'];

    header("Content-Description: File Transfer");
    header("Content-Type: {$file_info['mime']}");
    header('Content-Length: ' . $file_info['size'] );
    header('Content-Disposition: attachment; filename="' . $file_info['basename'] . '"');
    header('Content-Transfer-Encoding: binary');

    $stream = fopen($file_to_download, 'rb');
    while(!feof($stream)) {
      print fread($stream, 1024);
      flush(); ob_flush();
    }
    fclose ($stream);
    exit;
  }




//*******************************************************
// GENERIC METHODS: They can be used without setting file

  function get_mime($extension=null){
    return (empty($extension) || ! array_key_exists($extension, $this->mimes) ) ?
      "unknown/$extension"
    :
      $this->mimes[$extension];
  }

  function get_any_file_details($file=null){
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