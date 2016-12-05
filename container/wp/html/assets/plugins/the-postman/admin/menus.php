<?php

/* ----------------------------------------------------------
  
  Admin Panel
  
---------------------------------------------------------- */

add_action( 'admin_menu', 'postman_admin_menu', 9 );

function postman_admin_menu()
{
  
  $icon_url = '';

  if ( defined( 'MP6' ) && MP6 || version_compare( get_bloginfo( 'version' ), '3.8-dev', '>=' ) ) {
    $icon_url = 'dashicons-email-alt';
  }
  
  add_object_page(
    __( 'the Postman', 'the-postman' ),
    __( 'the Postman', 'the-postman' ),
    'edit_posts',
    'postman',
    'the_postman_manage_form',
    $icon_url
  );
  
  $edit = add_submenu_page(
    'postman',
    __( 'Form Elements', 'the-postman' ),
    __( 'Manage Form Elements', 'the-postman' ),
    'edit_posts',
    'postman',
    'the_postman_manage_form'
  );
  
  #add_action( 'load-'.$edit, 'the_postman_backyard' );

  $add = add_submenu_page(
    'postman',
    __( 'Mail Templates', 'the-postman' ),
    __( 'Manage Mail Templates', 'the-postman' ),
    'edit_posts',
    'postman-mail-tpl',
    'the_postman_manage_mail_template'
  );

  #add_action( 'load-'.$add, 'the_postman_backyard' );
  
  
  
}


function the_postman_manage_form()
{
  
  $postman = new Postman();
  
  $rules = array();
  foreach($postman->rules as $objectname)
  {
    $rules[] = array(
      'name' => $objectname,
      'label' => $postman->$objectname->label,
    );
  }
  
  if ($_POST && wp_verify_nonce($_POST['_wpnonce'], 'the-postman'))
  {
    $fields = array();
    foreach ($_POST['field'] as $field)
    {
      $vals = array();
      if ($field['name'])
      {
        
        if ($field['rules'])
        {
          
          foreach ($field['rules'] as $rule)
          {
            $vals[$rule] = array(
              'slug' => $rule,
              'value' => '',
            );
          }
          
        }
        
        $fields[$field['name']] = array(
          'label' => $field['label'],
          'name' => $field['name'],
          'rules' => $vals,
        );
      }
    }
    
    update_option('the-postman-fields', $fields);
    
  }
  
  // 管理画面の出力
  echo '<div class="wrap">';

  echo '<h2>'.__( 'the Postman', 'the-postman' ).'</h2>';
  
  $fields = array();
  foreach (get_option('the-postman-fields', array()) as $option)
  {
    $fields[] = $option;
  }
  
  $fields[count($fields)] = array();
  
?>

<form name="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']?>">

<?php wp_nonce_field('the-postman') ?>

<?php foreach($fields as $key => $value):?>

<table class="form-table">
  <tbody>
    <tr valign="top">
      <th scope="row"><label for="for_label"><?php _e('Label', 'the-postman')?></label></th>
      <td><input name="field[<?php echo $key?>][label]" type="text" id="for_label" value="<?php echo $value['label']?>" class="regular-text"></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="for_name"><?php _e('Name', 'the-postman')?></label></th>
      <td><input name="field[<?php echo $key?>][name]" type="text" id="for_name" value="<?php echo $value['name']?>" class="regular-text"></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="for_name"><?php _e('Rules', 'the-postman')?></label></th>
      <td>
        <?php foreach($rules as $rule):?><p><label><input name="field[<?php echo $key?>][rules][]" type="checkbox" id="for_validations" value="<?php echo $rule['name']?>"<?php if(isset($value['rules'][$rule['name']])):?> checked="checked"<?php endif?>> <?php echo $rule['label']?></label></p><?php endforeach?>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2"><a href="#" class="button"><?php _e('Delete It', 'the-postman')?></a></td>
    </tr>
  </tbody>
</table>

<hr>

<?php endforeach?>

<table class="form-table">
  <tbody>
    <tr valign="top">
      <td colspan="2"><a href="#" class="button"><?php _e('Add New', 'the-postman')?></a></td>
    </tr>
  </tbody>
</table>

<hr>

<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save', 'the-postman')?>"></p>

</form>

<?php
  
  echo '</div>';
  
}

/* ----------------------------------------------------------
	
	the Postman > Mail Templates
	
---------------------------------------------------------- */

function the_postman_manage_mail_template()
{
  
  if ($_POST && wp_verify_nonce($_POST['_wpnonce'], 'the-postman'))
  {
    
    $tpls = array();
    foreach ($_POST['tpl'] as $tpl)
    {
      if ($tpl['type'])
      {
        
        $tpls[$tpl['type']] = array(
          'type' => $tpl['type'],
          'subject' => $tpl['subject'],
          'from' => $tpl['from'],
          'to' => $tpl['to'],
          'body' => $tpl['body'],
        );
      }
    }
    
    update_option('the-postman-mail-templates', $tpls);
    
  }
  
  // 管理画面の出力
  echo '<div class="wrap">';

  echo '<h2>'.__( 'Mail Templates', 'the-postman' ).'</h2>';
  
  $tpls = get_option('the-postman-mail-templates');
  
  $tpls[] = array(
      'type' => 'mail'.(count($tpls)+1),
      'subject' => '',
      'from' => '',
      'to' => '',
      'body' => 'MAIL',
  );
  
?>

<form name="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']?>">

<?php wp_nonce_field('the-postman') ?>

<?php foreach($tpls as $key => $value):?>

<table class="form-table">
  <tbody>
    <tr valign="top">
      <th scope="row"><label for="for_type"><?php _e('Type', 'the-postman')?></label></th>
      <td><input name="tpl[<?php echo $key?>][type]" type="text" id="for_type" value="<?php echo $value['type']?>" class="large-text"></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="for_subject"><?php _e('Subject', 'the-postman')?></label></th>
      <td><input name="tpl[<?php echo $key?>][subject]" type="text" id="for_subject" value="<?php echo $value['subject']?>" class="large-text"></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="for_from"><?php _e('From', 'the-postman')?></label></th>
      <td><input name="tpl[<?php echo $key?>][from]" type="text" id="for_from" value="<?php echo $value['from']?>" class="large-text"></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="for_to"><?php _e('To', 'the-postman')?></label></th>
      <td><input name="tpl[<?php echo $key?>][to]" type="text" id="for_to" value="<?php echo $value['to']?>" class="large-text"></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="for_body"><?php _e('Template', 'the-postman')?></label></th>
      <td>
        <textarea id="for_body" name="tpl[<?php echo $key?>][body]" class="large-text" rows="22"><?php echo $value['body']?></textarea>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2"><a href="#" class="button"><?php _e('Delete It', 'the-postman')?></a></td>
    </tr>
  </tbody>
</table>

<hr>

<?php endforeach?>

<table class="form-table">
  <tbody>
    <tr valign="top">
      <td colspan="2"><a href="#" class="button"><?php _e('Add New', 'the-postman')?></a></td>
    </tr>
  </tbody>
</table>

<hr>

<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save', 'the-postman')?>"></p>

</form>

<?php
  
  echo '</div>';
  
}

function the_postman_backyard()
{
  
  // 管理画面で保存する時
  
}