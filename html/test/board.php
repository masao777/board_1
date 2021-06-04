<?php

require_once('config.php');
require_once('function.php');


//タイムゾーン
date_default_timezone_set('Asia/Tokyo');

//変数
$now_date=null;
$data=null;
$file_handle=null;
$split_data=null;
$message=array();
$message_array=array();
$error_message=array();
$clean=array();
$view_data=array();
$value=array();

//セッション
session_start();

if(!empty($_POST['submit'])){

  //入力チェック
  if(empty($_POST['view_name'])){
    $error_message[]='名前を入力してください。';
  }else{
    $clean['view_name']=h($_POST['view_name']);
    //セッションに名前を保存
    $_SESSION['view_name'] = $clean['view_name'];
  }
  if(empty($_POST['message'])){
    $error_message[]='内容を入力してください。';
  }else{
    $clean['message']=h($_POST['message']);
  }
  if(empty($error_message)){
  //書き込み
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    if($mysqli->connect_errno){
      $error_message[]='書き込みに失敗しました。エラー番号'.$mysqli->connect_errno.':'.$mysqli->connect_error;
    }else{
      $mysqli->set_charset('utf8');
      $now_date=date("Y-m-d H:i:s");

      //データの登録
      $sql="insert into message (view_name,message,post_date,board_id) values ('$clean[view_name]','$clean[message]','$now_date','$_GET[id]')";
      $res=$mysqli->query($sql);
      if($res){
        $_SESSION['$success_message'] = '書き込みに成功しました。';
      }else{
        $error_message[] = '書き込みに失敗しました。。';
      }
      $mysqli->close();
    }
      // header('Location:./');
    }
  }
//読み込み
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
if($mysqli->connect_errno){
  $error_message[]='読み込みに失敗しました。エラー番号'.$mysqli->connect_errno.':'.$mysqli->connect_error;
}else{
  $board_id = $_GET["id"];
  $mysqli->set_charset('utf8');
  $sql="select view_name,message,post_date from message where board_id = $board_id order by post_date desc";
  $res=$mysqli->query($sql);

  if($res){
    $message_array = $res->fetch_all(); //MYSQL_ASSOC
  }

  $mysqli->close();


}
require_once("page.php");


 ?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>掲示板</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h1>掲示板</h1>
    <?php if(!empty($_POST['submit']) && !empty($_SESSION['$success_message'])): ?>
      <p class="success_message"><?php echo $_SESSION['$success_message'] ?></p>
      <?php unset($_SESSION['$success_message']); ?>
    <?php endif; ?>
    <?php if(!empty($error_message)): ?>
      <ul class="error_message">
        <?php foreach ($error_message as $value): ?>
        <li><?php echo $value ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <div class="form">
      <form  method="post" >
        <div class="name">
          <label for="view_name">名前</label>
          <input id="view_name" type="text" name="view_name">
        </div>
        <div class="message">
          <label for="message">内容</label>
          <textarea id="mesage" name="message"></textarea>
        </div>
        <input type="submit" name="submit" value="書き込み" class="submit">
      </form>
    </div>
    <hr>
    <section>
      <?php if(!empty($view_page)): ?>
      <?php foreach($view_page as $value): ?>
      <article class="">
        <div class="info">
          <img class= "images" src="https://joeschmoe.io/api/v1/random">
          <h2><?php echo $value[0]; ?></h2>
          <time><?php echo date('Y年m月d日 H:i',strtotime($value[2])); ?></time>
          <p><?php echo nl2br($value[1]); ?></p>
        </div>
      </article>
      <?php endforeach; ?>
      <?php endif; ?>
    </section>
    <div class="page">
      <?php  if ($page > 1): ?>
        <a href="board.php?page=<?php echo ($page-1); ?>&id=<?php echo $board_id ?>" class="page_btn">前のページへ</a>
      <?php endif; ?>
      <?php  if ($page < $max_page): ?>
        <a href="board.php?page=<?php echo ($page+1); ?>&id=<?php echo $board_id ?>" class="page_btn">次のページへ</a>
      <?php endif; ?>
    </div>
    <a href="admin.php">管理ページ</a>
    <a href="index.php">トップページ</a>
  </body>
</html>
