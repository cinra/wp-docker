<?php

function get_wp_search_date($statuses = array(), $relation = 'AND', $orderby = 'start_date', $order = 'DESC', $sticky_status = null)
{
  if(!$statuses) return false;
  if($relation === 'AND' && $sticky_status) return false;

  $results = get_posts_by_date_status($statuses, $orderby, $order);
  if($results == false) return false;

  switch (count($results)) {
    case 1:
      $ids = $results[0]['ids'];
      break;

    case 2:
      if($relation == "OR")
      {
        if($results[0]['status'] == $sticky_status)
        {
          $sticky_ids = $results[0]['ids'];
          $merged_ids = $results[1]['ids'];
        }
        else
        {
          $sticky_ids = $results[1]['ids'];
          $merged_ids = $results[0]['ids'];
        }
        $ids = array_merge($sticky_ids, $merged_ids);
      }
      else
      {
        $ids = array_intersect($results[0]['ids'], $results[1]['ids']);
      }
      break;

    default:
      $ids = get_all_events_by_status('start_date', 'DESC', 'opened');
      break;
  }
  return $ids;
}

function get_posts_by_date_status($statuses, $orderby, $order)
{
  global $wpdb;
  $table_name = SEARCH_DATE_TABLE;
  $now = time();

  foreach($statuses as $status)
  {
    $where = '';
    switch ($status) {
      case 'future':
        $where = "start_date > ". $now;
        break;

      case 'opened':
        $where = "start_date <= ". $now. " AND end_date >= ". $now;
        break;

      case 'finished':
        $where = "end_date < ". $now;
        break;

      default:
        return false;
        break;
    }
    $ids = $wpdb->get_col("SELECT post_id FROM {$table_name} WHERE {$where} ORDER BY {$orderby} {$order}");
    $results[] = array(
                       'status' => $status,
                       'ids' => $ids,
                       );
  }
  return $results;
}

function get_wp_search_index_value($post_id = null, $culmun = array(), $orderby = 'post_id', $order = 'DESC')
{
  if(!$post_id) return false;
  $select = ($culumn) ? implode(', ', $culumn) : '*';

  return $wpdb->get_col("SELECT {$select} FROM {$table_name} WHERE post_id = {$post_id} ORDER BY {$orderby} {$order}");
}

function get_all_events_by_status($orderby = 'start_date', $order = 'DESC', $sticky_status = null)
{
  $results = array();
  $statuses = static_event_status(true);

  if($sticky_status && $k = array_search($sticky_status, $statuses))
  {
    unset($statuses[$k]);
    array_unshift($statuses, $sticky_status);
  }

  foreach($statuses as $status)
  {
    $get_result = get_posts_by_date_status(array($status), $orderby, $order);
    if($get_result) $results = array_merge($results, $get_result[0]['ids']);
  }

  return $results;
}
