<?php

include_once(POSTMAN_BASE_PATH.'/rule.class.php');

class Postman_Rule_Required extends Postman_Rule
{

  public function __construct()
  {

    $this->label = __( 'Required', 'the-postman' );
    $this->error_message = __( '必須項目です', 'the-postman' );

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

    if (empty($val)) return parent::do_error();

    return $result;

  }



}