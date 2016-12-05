<?php

/* ----------------------------------------------------------
  
  Postman
  
---------------------------------------------------------- */

$_POST['text'] = 'TEXT TEST';
$_POST['email'] = 'pelepop@gmail.com';
$_POST['deny'] = '';
$_POST['body'] = '[email]からのメールです';

class PostmanTest extends PHPUnit_Framework_TestCase
{
  
  public function testGet()
  {
    
    $postman = new Postman();
    
    $this->assertNotNull($postman->get('deny'));
    $this->assertNull($postman->get('null'));
    
  }
  
  public function testValidate()
  {
    
    $postman = new Postman();
    
    $results = $postman->validate();
    
    $this->assertNotEmpty($postman->vals);
    
    $this->assertNotEmpty($results);
    $this->assertEquals($results['values']['text']['status'], 'success');
    $this->assertEquals(null, $results['values']['text']['message'][0]);
    
    $this->assertNotNull($postman->get('deny'));
    $this->assertEmpty($postman->get('deny'));
    $this->assertNotEmpty($results['values']['deny']);
    $this->assertEquals($results['values']['deny']['status'], 'error');
    $this->assertEquals(__( 'This field is required!', 'the-postman' ), $results['values']['deny']['message'][0]);
    
  }
  
  
  public function testMail()
  {
    
    global $postman;
    
    $postman = new Postman;
    
    $fields = get_option('the-postman-fields');

    foreach ($fields as $key => $field)
    {
      add_shortcode($key, 'the_postman_shortcode');
    }
    
    unset($_POST['deny']);
    
    
    
    $this->assertEquals('pelepop@gmail.com', $postman->get('email'));
    $this->assertTrue(function_exists('do_shortcode'));
    
    $results = $postman->validate();
    
    $this->assertTrue($postman->mail());
    
  }
  
  
  public function testValidateRequired()
  {
    
    $val = new Postman_Rule_Required();
    
    $result1 = $val->do_validate('');
    
    $this->assertNotEmpty($result1);
    $this->assertEquals($result1['status'], 'error');
    
    $result2 = $val->do_validate('TEST');
    
    $this->assertEquals($result2['status'], 'success');
    
  }
  
  

  
}