<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Addons_Repository_Model_Cells extends Module_Core_Repository_Model_Abstract {

  function get_cells($current_page = 1, $enabled_only = true){
    $select = $this->_db->select()
                   ->from( array('c'   => 'cells') )
                   ->join( array('z'  => 'zone_sector_view'), 'z.sector_id = c.sector',  array('zone_id','zone','sector_id','sector') )
                   ->where('c.lang = ?', App::locale()->getLang() )
                   ->order('c.zone ASC');

    if( $enabled_only === true ){
      $select->where('c.status = ?', "enabled");
    }

    $cells = empty($current_page) ?
      $this->_db->query( $select )->fetchAll()
    :
      $this->setPaginator_page($current_page)->paginate_query( $select )
    ;

    return empty($cells)? null : $cells;
  }

}