<?php
class Module_Core_Repository_Model_Filesystem extends Core_Model_Repository_Model{

  private $file	 = null;
  private $mimes = null;

  private $uploads_folder         = null;
  private $uploaded_file_size     = null;
  private $uploaded_file_max_size = 10485760;

  function init(){
    $this->mimes         = $this->_module->getConfig('core', 'mime');
    $this->uploads_folder = WP . DS . "media" .DS . $this->_module->getConfig('core', 'uploads_folder') . DS . $this->_module->getModel('Dates')->toDate("10", date('Y-m-d h:i:s')) . DS;
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



  function image_upload($file=null){
    $checks     = $this->check_uploads_settings($file);
    if( is_array($checks) ){
      return json_encode($checks);
    }

    $was_uploaded = $this->upload();

    return empty($was_uploaded) ?
             json_encode(array('error'=> 'Could not save uploaded file.' . 'The upload was cancelled, or server error encountered'))
           :
             json_encode(array('success'=>true));
  }

  function article_images_upload(){

  }

  private function check_uploads_settings($file){
    if( empty($file) ){
      return array('error' => "No files were uploaded.");
    }

    if( $this->check_directory() === false ){
      return array('error' => "Server error. Upload directory isn't writable.");
    }

    // Getting content length from server
    if( ! empty($_SERVER["CONTENT_LENGTH"]) ){
      $this->uploaded_file_size = $_SERVER["CONTENT_LENGTH"];

      if ($this->uploaded_file_size == 0) {
        return array('error' => 'File is empty');
      }

      if ($this->uploaded_file_size > $this->uploaded_file_max_size) {
        return array('error' => 'File is too large');
      }
    }

    $pathinfo  = pathinfo($file);
    $filename  = $pathinfo['filename'];
    $extension = @$pathinfo['extension'];		// hide notices if extension is empty

    if( ! array_key_exists($extension, $this->mimes) ){
      $these = implode(', ', array_keys($this->mimes));
      return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
    }

    $this->file = $this->uploads_folder . $file;
    return true;
  }

  private function check_directory() {
    if ( ! file_exists($this->uploads_folder)) {
      if ( ! mkdir($this->uploads_folder,0777,true)) {
        return false;
      }
    }
    if ( ! is_writable($this->uploads_folder)) {
      return false;
    }
    return true;
  }

  private function upload(){
    $input    = fopen("php://input", "r");
    $temp     = tmpfile();
    $realSize = stream_copy_to_stream($input, $temp);
    fclose($input);

    if ($realSize != $this->uploaded_file_size ){
      return false;
    }

    $target = fopen($this->file, "w");
    fseek($temp, 0, SEEK_SET);
    stream_copy_to_stream($temp, $target);
    fclose($target);
    return true;
  }

}