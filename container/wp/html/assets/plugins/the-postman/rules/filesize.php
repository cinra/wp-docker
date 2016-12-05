<?php

include_once(POSTMAN_BASE_PATH.'/rule.class.php');

/**
 * Postman_Rule_Required class.
 * 
 * @extends Postman_Rule
 */
class Postman_Rule_Filesize extends Postman_Rule
{
  
  /**
   * __construct function.
   * 
   * @access public
   * @return void
   */
  public function __construct()
  {
    
    $this->label = __( 'File Size', 'the-postman' );
    $this->error_message = __( 'This file must be more smaller', 'the-postman' );
    
  }
  
  /**
   * do_validate function.
   * 
   * @access public
   * @param mixed $val (default: null)
   * @return void
   */
  public function do_validate($val = null, $param = null)
  {
    
    $result = parent::do_validate($val);
    
    if (!isset($val['size']) || $val['size'] > $param) return parent::do_error();
    
    return $result;
    
  }
  
  
  
}