<?php

require_once('config.php');
require_once('function.php');
require_once('weather-api.php');



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
  if(empty($_POST['board_name'])){
    $error_message[]='スレッド名を入力してください。';
  }else{
    $clean['board_name']=h($_POST['board_name']);
    //セッションに名前を保存
    $_SESSION['board_name'] = $clean['board_name'];
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
      $sql="insert into board (board_name,create_date) values ('$clean[board_name]','$now_date')";
      $res=$mysqli->query($sql);
      if($res){
        $_SESSION['$success_message'] = '書き込みに成功しました。';
      }else{
        $error_message[] = '書き込みに失敗しました。。';
      }
      $mysqli->close();
    }
      header('Location:./');
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
require_once("page.php");


 ?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>掲示板</title>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body>
    <h1>掲示板</h1>
    <div class = "weather">
      <h3>現在の天気</h3>
      <p><?php echo $weather_place?></p>
      <img src= "<?php echo $weather_icon ?>" >
      <p><?php echo $weather_temp ?>℃</p>
      <p><?php echo $weather_now?></p>
    </div>
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
          <label for="board_name">スレッド名</label>
          <input id="board_name" type="text" name="board_name" >
        </div>
        <input type="submit" name="submit" value="作成" class="submit">
      </form>
    </div>
    <hr>
    <section>
      <?php if(!empty($view_page)): ?>
      <?php foreach($view_page as $value): ?>
      <article class="">
        <div class="info">
          <h2><a href = "board.php?id=<?php echo $value[0]?>"><?php echo $value[1]; ?></a></h2>
          <time><?php echo date('Y年m月d日 H:i',strtotime($value[2])); ?></time>
        </div>
      </article>
      <?php endforeach; ?>
      <?php endif; ?>
    </section>
    <div class="page">
      <?php  if ($page > 1): ?>
        <a href="index.php?page=<?php echo ($page-1); ?>" class="page_btn">前のページへ</a>
      <?php endif; ?>
      <?php  if ($page < $max_page): ?>
        <a href="index.php?page=<?php echo ($page+1); ?>" class="page_btn">次のページへ</a>
      <?php endif; ?>
    </div>
    <a href="admin.php">管理ページ</a>
  </body>
</html>
