<?php

//変数
$max = null;
$contents_sum = null;
$max_page = null;
$page = null;
$start = null;
$view_page = array();


$max = 10; //コンテンツの最大数


if (is_countable($message_array)) {
  $contents_sum = count($message_array);
} //コンテンツの総数
  $max_page = ceil($contents_sum / $max); //ページの最大値

  if (!isset($_GET['page'])) {
    $page = 1;
  } else {
    $page = $_GET['page'];
  }

  $start = $max * ($page - 1); //スタートするページを取得
  // $view_page = array_slice($message_array, $start, $max, true);
  if (is_countable($message_array)){
    $view_page = array_slice($message_array, $start, $max, true);
  } //表示するページを取得

 ?>
