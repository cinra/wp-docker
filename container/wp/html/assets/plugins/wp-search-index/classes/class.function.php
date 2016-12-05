<?php

function get_wp_search_index($get = array(), $terms = array(), $relation = 'AND', $orderby = 'post_id')
{
  // $options = array();
  $is_isset = array();
  $results = array();

  foreach($terms as $key => $value)
  {

    if(isset($get[$key]) && $get[$key])
    {
      $terms[$key]['query'] = $get[$key];
      $is_isset[] = (is_array($value['column'])) ? $key : $value['column'];

      if(is_array($terms[$key]['query']))
      {
        array_walk($terms[$key]['query'], function(&$i){ $i = esc_html($i); });
      }
      else
      {
        $terms[$key]['query'] = array(esc_html($terms[$key]['query']));
      }

      $options = array();
      $options[] = array(
                        'relation'  => 'OR',
                        'key'       => $value['column'],
                        'value'     => $terms[$key]['query'],
                        'orderby'   => $orderby,
                        'unified_key' => (is_array($value['column'])) ? $key : false,
                        );
      $results = array_merge($results,get_posts_by_terms($options));
    }
  }

  // $results = get_posts_by_terms($options);
  $ids = array();
  /* TODO: 別functionにする */
  switch(count($is_isset))
  {
    case 1:
      if(isset($results[$is_isset[0]]) && $results[$is_isset[0]]) $ids = $results[$is_isset[0]];
      break;

    case 2:
    case 3:
    case 4:
      for ($i=0; $i < count($is_isset)-1; $i++) {
        if($i < 1)
        {
          if($relation == "OR")
          {
            $ids = array_merge($results[$is_isset[$i]], $results[$is_isset[$i+1]]);
          }
          else
          {
            $ids = array_intersect($results[$is_isset[$i]], $results[$is_isset[$i+1]]);
          }
        }
        else
        {
          if($relation == "OR")
          {
            $ids = array_merge($ids, $results[$is_isset[$i+1]]);
          }
          else
          {
            $ids = array_intersect($ids, $results[$is_isset[$i+1]]);
          }
        }
      }
      break;

    default:
      break;
  }
  return $ids;
}

function get_posts_by_terms($options = array())
{
  if(!$options) return false;

  global $wpdb;
  $table_name = SEARCH_INDEX_TABLE;

  foreach($options as $option)
  {
    if(!is_array($option['key'])) $ids[$option['key']] = array();
    $where = '';

    if(!$option['key'] || !$option['value']) break;
    $relation = ($option['relation']) ? $option['relation'] : 'AND';

    if(is_array($option['key']) && $option['unified_key'])
    {
      $s = 0;
      if(is_array($option['value'])) $option['value'] = $option['value'][0];

      foreach($option['key'] as $k)
      {
        if($s < 1)
        {
          $where = $k. " LIKE '%". $option['value']. "%'";
        }
        else
        {
          $where .= ' OR '. $k. " LIKE '%". $option['value']. "%'";
        }
        $s++;
      }
      $option['key'] = $option['unified_key'];
    }
    elseif(is_array($option['value']))
    {
      foreach($option['value'] as $v)
      {
        // if(isset($option['string']) && $option['string'] !== false)
        if($option['key'] == 'acf_en_title')
        {
          // $arr_where[] = $option['key']." = '". $v. "'";
          $arr_where[] = $option['key']." LIKE '%". $v. "%'";
        }
        else
        {
          $arr_where[] = $option['key']." LIKE '%*[". $v. "]*%'";
        }
      }
      $where = implode(' '.$relation.' ', $arr_where);
    }
    else
    {
      // if(isset($option['string']) && $option['string'] !== false)
      if($option['key'] == 'acf_en_title')
      {
        // $where = $option['key']. " = '". $option['value']. "'";
        $where = $option['key']. " LIKE '%". $option['value']. "%'";
      }
      else
      {
        $where = $option['key']. " LIKE '%*[". $option['value']. "]*%'";
      }
    }

    $filter_category_name = 'dictionary';
    if (is_category($filter_category_name)) {
      $where = "(" . $where . ")" . " AND category LIKE '%*[". $filter_category_name. "]*%'";
    }

    $orderby = (isset($option['orderby']) && $option['orderby']) ? $option['orderby'] : 'post_id';

    $ids[$option['key']] = $wpdb->get_col("SELECT post_id FROM {$table_name} WHERE {$where} ORDER BY {$orderby}");

  }
  return ($ids) ? $ids : false;
}



/*
$optionフォーマット

$options = array(
                array(
                      'relation'  => 'OR',//AND
                      'key'       => 'acf_service',
                      'value'     => array('sv_1', 'sv_2'),
                      ),
                array(
                      'relation'  => 'OR',//AND
                      'key'       => 'acf_tag',
                      'value'     => array('tg_1', 'tg_2'),
                      ),
                );
                array(
                      'relation'  => 'OR',
                      'key'       => 'acf_en_title',
                      'value'     => 'TEST',
                      'string'    => true,
                      ),
                array(
                      'relation'  => 'OR',
                      'key'       => 'acf_type',
                      'value'     => array('01', '02'),
                      'orderby'   => 'acf_en_title ASC',
                      ),
                );
 */