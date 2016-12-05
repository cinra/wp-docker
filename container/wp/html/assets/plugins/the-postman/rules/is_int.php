<?php

include_once(POSTMAN_BASE_PATH.'/rule.class.php');

class Postman_Rule_Is_int extends Postman_Rule
{
  
  public function __construct()
  {
    
    $this->label = __( 'Valid Interger', 'the-postman' );
    $this->error_message = __( 'This field has to be a integer', 'the-postman' );
    
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
    
    if (!empty($val))
    {
      
      if (filter_var($val, FILTER_VALIDATE_INT)) return $result;
    
      return parent::do_error();
      
    }
    
    return $result;
    
  }
  
  
  
}