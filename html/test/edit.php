<?php
require_once('config.php');
require_once('function.php');


//タイムゾーン
date_default_timezone_set('Asia/Tokyo');

//変数
$message_id = null;
$mysqli = null;
$sql = null;
$res = null;
$error_message = array();
$message_data = array();

//セッション
session_start();


//ログインしているか確認
if(empty($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true){
  //ログインページに移動
  header("Location./admin.php");
}

if(!empty($_GET['message_id']) && empty($_POST['message_id'])){

  $message_id = (int)h($_GET['message_id']);

  //mysqlに接続
  $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

  if($mysqli->connect_errno){
    $error_message[]='接続に失敗しました。エラー番号'.$mysqli->connect_errno.':'.$mysqli->connect_error;
  }else{
    $sql = "select * from message where id = $message_id";
    $res = $mysqli -> query($sql);

    if($res){
      $message_data = $res -> fetch_assoc();
    }else{
      header("Location:./admin.php");
    }
    $mysqli -> close();
  }
}elseif (!empty($_POST['message_id'])) {

  $message_id = (int)h($_POST['message_id']);

  if(empty($_POST['view_name'])){
    $error_message[] = '名前を入力してください。';
  }else{
    $message_data['view_name'] = h($_POST['view_name']);
  }

  if(empty($_POST['message'])){
    $error_message[] = '内容を入力してください。';
  }else{
    $message_data['message'] = h($_POST['message']);
  }

  if(empty($error_message)){

    //データベース更新
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

    if($mysqli->connect_errno){
      $error_message[]='接続に失敗しました。エラー番号'.$mysqli->connect_errno.':'.$mysqli->connect_error;
    }else{
      $sql = "update message set view_name = '$message_data[view_name]',message = '$message_data[message]'
      where id = $message_id";
      $res = $mysqli -> query($sql);
  }
  $mysqli->close();
  if($res){
    header("Location:./admin.php");
  }
}
}
 ?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>掲示板 投稿の編集（管理）</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h1>編集</h1>
    <?php if(!empty($error_message)): ?>
      <ul class="error_message">
        <?php foreach ($error_message as $value): ?>
        <li><?php echo $value ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <div class="form">
      <form  method="post">
        <div class="">
          <label for="view_name">名前</label>
          <input id="view_name" type="text" name="view_name" value="<?php if(!empty($message_data['view_name'])){
            echo $message_data['view_name'];
          } ?>">
        </div>
        <div class="">
          <label for="message">内容</label>
          <textarea id="mesage" name="message"><?php if(!empty($message_data['message'])){
            echo $message_data['message'];
          } ?></textarea>
        </div>
        <a class="btn_cancel" href="admin.php">キャンセル</a>
        <input type="submit" name="submit" value="更新" class="submit">
        <input type="hidden" name="message_id" value="<?php echo $message_data["id"]; ?>">
      </form>
    </div>
  </body>
</html>
