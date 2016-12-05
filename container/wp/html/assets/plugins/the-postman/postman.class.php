<?php

/* ----------------------------------------------------------

  Postman

---------------------------------------------------------- */

class Postman
{

  public $vals = array();
  public $templates = array();
  public $rules = array();
  private $posts = array();
  private $files = array();
  private $results;

  // TODO: 値の検証／バリデーション。DBに登録されていない値は破棄するように。

  function validate()
  {

    $this->results = array(
      'status' => 'success',
      'values' => array(),
    );

    if ($this->vals)
    {

      foreach ($this->vals as $key => $val)
      {

        $message = array();
        $status = 'success';

        // $_POST
        if (null !== ($post = $this->get($key)))
        {

          if ($val['rules'])
          {
            if (!empty($this->$rule['slug']))
            {
              foreach ($val['rules'] as $rule)
              {
                $result = $this->{$rule['slug']}->do_validate($post, $rule['value']);
                if ($result['status'] !== 'success')
                {
                  $status = $result['status'];
                  $message[] = $result['message'];
                  $this->results['status'] = 'error';
                }
              }
            }
          }

          $this->results['values'][$key] = array(
            'status' => $status,
            'value' => $post,
            'message' => $message,
          );
        }

        // $_FILES
        if (null !== ($post = $this->get_file($key)))
        {

          $status = 'success';

          if ($val['rules'])
          {
            foreach ($val['rules'] as $rule)
            {
              $result = $this->$rule['slug']->do_validate($post, $rule['value']);
              if ($result['status'] !== 'success')
              {
                $status = $result['status'];
                $message[] = $result['message'];
                $this->results['status'] = 'error';
              }
            }
          }

          $this->results['values'][$key] = array(
            'status' => $status,
            'value' => $post,
            'message' => $message,
          );

        }
      }

    }

    // echo '<pre>';print_r($this->results);echo '</pre>';exit;

    return $this->results;

  }


  function mail()
  {

    if (!$this->results) return false;

    $result = true;

    $templates = $this->get_templates();
    $types = $this->get('_type');

    if (!$types) return true;

    if (!is_array($types)) $types = array($types);

    foreach ($types as $type)
    {

      if (array_key_exists($type, $templates))
      {

        $template = $templates[$type];

        $to = do_shortcode($template['to']);
        $subject = do_shortcode($template['subject']);
        $body = $this->compose_mail($template['body']);

        $args = array(
          'to' => $to,
          'subject' => $subject,
          'body' => $body,
          'is_spam' => false,
        );

        do_action_ref_array( 'postman_set_mail', array( &$args ) );

        $headers = array();
        if ($template['from']) $headers[] = 'From: '.do_shortcode($template['from']);

        if (!$body) return false;
        if ($arg['is_spam'] === true) return false;

        // Attachments
        $attachments = array();
        if ($this->files)
        {
          foreach ($this->files as $file)
          {
            $filepath = WP_CONTENT_DIR .'/uploads/' . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $filepath);
            $check = wp_check_filetype($filepath);
            if ( $check['ext'] && $check['type'] ) $attachments[] = $filepath;
          }
        }

        $result = @wp_mail($to, $subject, $body, implode("\r\n", $headers), $attachments);

      }
    }

    return $result;

  }

  function is_error($key = null)
  {

    if (!isset($this->results)) return false;

    if (!$key) return ($this->results['status'] === 'error') ? true : false;

    return ($this->results['values'][$key]['status'] === 'error') ? true : false;

  }

  function get_message($key = null)
  {

    if (!isset($this->results)) return false;

    if (!$key) return isset($this->results['message']) ? $this->results['message'] : '';

    return isset($this->results['values'][$key]['message']) ? $this->results['values'][$key]['message'] : '';

  }

  function compose_mail($template = null)
  {

    if (!$template) return null;

    return do_shortcode($template);

  }


  function get($key = null)
  {

    if (!$key) return null;

    if (!isset($this->posts[$key])) return null;

    return !is_array($this->posts[$key]) ? stripslashes($this->posts[$key]) : $this->posts[$key];

  }

  function get_file($key = null)
  {

    if (!$key) return null;

    return isset($this->files[$key]) ? $this->files[$key] : null;

  }

  /**
   * set_vals function.
   *
   * @access public
   * @param mixed $vals (default: null)
   * @return void
   */
  function set_vals($vals = null)
  {

    if (!$vals) return false;

    $this->vals = array_merge($this->vals, $vals);

    return true;

  }

  /**
   * get_vals function.
   *
   * @access public
   * @return void
   */
  function get_vals()
  {

    return $this->vals;

  }

  /**
   * set_templates function.
   *
   * @access public
   * @param mixed $templates (default: null)
   * @return void
   */
  function set_templates($templates = null)
  {

    if (!$templates) return false;

    $this->templates = array_merge($this->templates, $templates);

    return true;

  }

  /**
   * get_templates function.
   *
   * @access public
   * @return void
   */
  function get_templates()
  {

    return $this->templates;

  }

  /**
   * __construct function.
   *
   * @access public
   * @return void
   */
  function __construct()
  {

    foreach (glob(dirname(__FILE__).'/rules/*.php') as $filepath)
    {
      include_once($filepath);
      $filename = basename(str_replace('.php', '', $filepath));
      $classname = 'Postman_Rule_'.ucfirst($filename);
      $this->$filename = new $classname();
      $this->rules[] = $filename;
    }

    $this->set_vals(get_option('the-postman-fields'));
    $this->set_templates(get_option('the-postman-mail-templates'));

    $this->posts = $_POST;
    $this->files = $_FILES;

    #echo '<pre>';print_r($this->posts);echo '</pre>';exit;

  }


}