<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Addons_Repository_Model_Comments extends Module_Core_Repository_Model_Abstract {

  function get_comment($id = 0){
    $select = $this->_db->select()
                   ->from( array('c'  => 'comments') )
                   ->join( array('cd' => 'comments_data'), 'cd.comment_id = c.id',  array('comment','author','email','likes','dislikes','reported','category') )
                   ->where('c.id = ?', $id);

    $comment = $this->_db->query( $select )->fetch();
    return empty($comment)? null : $comment;
  }

  function get_comments($id = null, $current_page = 1, $type='article', $enabled_only = true){
    $parent_comments = $this->get_parent_comments($id, $current_page, $type, $enabled_only);
    if( empty($parent_comments) ){
      return null;
    }

    foreach($parent_comments['items'] AS $key => $comment){
      $parent_comments['items'][$key]['replies'] = $this->get_replies($comment['id']);
    }

    return $parent_comments;
  }

  function get_parent_comments($id = null, $current_page = 1, $type='article', $enabled_only = true){
    $select = $this->_db->select()
                   ->from( array('c'  => 'comments') )
                   ->join( array('cd' => 'comments_data'), 'cd.comment_id = c.id',  array('comment','author','email','likes','dislikes','reported','category') )
                   ->where('c.reference = ?', $id)
                   ->where('c.parent_id = 0')
                   ->where('c.type = ?', $type)
                   ->order('c.created DESC');

    if( $enabled_only === true ){
      $select->where('c.status = ?', "enabled");
    }

    $comments = $this->setPaginator_page($current_page)->paginate_query( $select );;
    return empty($comments)? null : $comments;
  }

  function get_replies($id=NULL, $enabled_only = true){
    $select = $this->_db->select()
                   ->from( array('c'  => 'comments') )
                   ->join( array('cd' => 'comments_data'), 'cd.comment_id = c.id',  array('comment','author','email','likes','dislikes','reported','category') )
                   ->where('c.parent_id = ?', $id)
                   ->order('c.child_id ASC');

    if( $enabled_only === true ){
      $select->where('c.status = ?', "enabled");
    }

    $replies = $this->_db->query( $select )->fetchAll();
    return empty($replies)? array() : $replies;
  }

}