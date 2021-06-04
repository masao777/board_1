<?php

//ログインパス
define('PASSWORD','password');

require_once('config.php');

//タイムゾーン
date_default_timezone_set('Asia/Tokyo');

$now_date=null;
$data=null;
$file_handle=null;
$split_data=null;
$message=array();
$message_array=array();
$success_message=null;
$error_message=array();
$clean=array();

//セッション
session_start();

//ログアウト
if(!empty($_GET['btn_logout'])){
  unset($_SESSION['admin_login']);
}

if(!empty($_POST['btn_submit'])){
  if(!empty($_POST['admin_password']) && $_POST['admin_password'] === "PASSWORD"){
    $_SESSION['admin_login'] = true;
  }else{
    $error_message[]='ログイン失敗。';
  }
}
//読み込み
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
if($mysqli->connect_errno){
  $error_message[]='読み込みに失敗しました。エラー番号'.$mysqli->connect_errno.':'.$mysqli->connect_error;
}else{
  $mysqli->set_charset('utf8');
  $sql="select id,board_name,create_date from board order by create_date desc";
  $res=$mysqli->query($sql);

  if($res){
    $message_array = $res->fetch_all(); //MYSQL_ASSOC
  }

  $mysqli->close();
}

 ?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>掲示板 管理</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h1>管理ページ</h1>
    <?php if(!empty($error_message)): ?>
      <ul class="error_message">
        <?php foreach ($error_message as $value): ?>
        <li><?php echo $value ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <hr>
    <section>
    <?php if(!empty($_SESSION['admin_login'])&&$_SESSION['admin_login']===true): ?>
      <?php if(!empty($message_array)): ?>
      <?php foreach($message_array as $value): ?>
      <article class="">
        <div class="info">
          <h2><a href = "./admin-board.php?board_id=<?php echo $value[0]?>"><?php echo $value[1]; ?></a></h2>
          <time><?php echo date('Y年m月d日 H:i',strtotime($value[2])); ?></time>
          <p class="ed"><a href = "edit.php?board_id=<?php echo $value[0]; ?>">編集</a><a href = "delete.php?board_id=<?php echo $value[0]; ?>">削除</a></p>
        </div>
      </article>
      <?php endforeach; ?>
      <?php endif; ?>
    <form class="" action="" method="get">
      <input type="submit" name="btn_logout" value="ログアウト" class="submit">
    </form>
    <?php else: ?>
      <div class="form">
        <form  method="post">
          <div class="">
            <label for="admin_password">パスワード</label>
            <input id="admin_password" type="password" name="admin_password" value="" >
          </div>
          <input type="submit" name="btn_submit" value="ログイン" class="submit">
        </form>
      </div>
      <a href="index.php" class="back">掲示板に戻る</a>
    <?php endif; ?>
    </section>
  </body>
</html>
