<?php

/* ----------------------------------------------------------
  
  Rule
  
---------------------------------------------------------- */

class Postman_Rule
{

  public $label = null;
  public $error_message = null;
  
  public function do_validate($val = null)
  {
    
    return array(
      'status' => 'success',
      'message' => null,
    );
    
  }
  
  public function do_error()
  {
    
    return array(
      'status' => 'error',
      'message' => $this->error_message,
    );
    
  }
  
}