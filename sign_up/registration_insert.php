<?php
session_start();

header("Content-type: text/html; charset=utf-8");

//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性あり";
	exit();
}

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//データベース接続
require_once("../commons.php");
$pdo = db_connect();

//エラーメッセージの初期化
$errors = array();

if(empty($_POST)) {
	header("Location: registration_mail_form.php");
	exit();
}

	$mail = $_SESSION['mail'];
	$user_id = $_SESSION['user_id'];
	$password = $_SESSION['password'];
	$wait = $_SESSION['wait'];
	$set_num = $_SESSION['set_num'];
	$menu_1 = $_SESSION['menu_1'];
	$training_Goal1 = $_SESSION['training_Goal1'];
	$menu_2 = $_SESSION['menu_2'];
	$training_Goal2 = $_SESSION['training_Goal2'];
	$menu_3 = $_SESSION['menu_3'];
	$training_Goal3 = $_SESSION['training_Goal3'];
	$mapx = $_SESSION['latitude'];
	$mapy = $_SESSION['longitude'];

//パスワードのハッシュ化
$password_hash =  password_hash($_SESSION['password'], PASSWORD_DEFAULT);

//ここでデータベースに登録する
try{
	//例外処理を投げる（スロー）ようにする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//トランザクション開始
	$pdo->beginTransaction();
	
	//memberテーブルに本登録する
	$statement = $pdo->prepare("INSERT INTO member (user_id,mail,password,wait,mapx,mapy) VALUES (:user_id,:mail,:password_hash,:wait,:mapx,:mapy)");
	//プレースホルダへ実際の値を設定する
	$statement->bindValue(':user_id', $user_id, PDO::PARAM_STR);
	$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	$statement->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
	$statement->bindValue(':wait', $wait, PDO::PARAM_STR);
	$statement->bindValue(':mapx', $mapx, PDO::PARAM_STR);
	$statement->bindValue(':mapy', $mapy, PDO::PARAM_STR);
	$statement->execute();
		
	//pre_memberのflagを1にする
	$statement = $pdo->prepare("UPDATE pre_member SET flag=1 WHERE mail=(:mail)");
	//プレースホルダへ実際の値を設定する
	$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	$statement->execute();
	
	
	//training_Goalテーブルに本登録する
	$statement2 = $pdo->prepare("INSERT INTO training_Goal (user_id,set_num,training_Goal1,training_Goal2,training_Goal3) VALUES (:user_id,:set_num,:training_Goal1,:training_Goal2,:training_Goal3)");
	//プレースホルダへ実際の値を設定する
	$statement2->bindValue(':user_id', $user_id, PDO::PARAM_STR);
	$statement2->bindValue(':set_num', $set_num, PDO::PARAM_STR);
	$statement2->bindValue(':training_Goal1', $training_Goal1, PDO::PARAM_STR);
	$statement2->bindValue(':training_Goal2', $training_Goal2, PDO::PARAM_STR);
	$statement2->bindValue(':training_Goal3', $training_Goal3, PDO::PARAM_STR);
	$statement2->execute();
	
	//training_Goalテーブルに本登録する
	$statement3 = $pdo->prepare("INSERT INTO training_Menu (user_id,set_num,menu_1,menu_2,menu_3) VALUES (:user_id,:set_num,:menu_1,:menu_2,:menu_3)");
	//プレースホルダへ実際の値を設定する
	$statement3->bindValue(':user_id', $user_id, PDO::PARAM_STR);
	$statement3->bindValue(':set_num', $set_num, PDO::PARAM_STR);
	$statement3->bindValue(':menu_1', $menu_1, PDO::PARAM_STR);
	$statement3->bindValue(':menu_2', $menu_2, PDO::PARAM_STR);
	$statement3->bindValue(':menu_3', $menu_3, PDO::PARAM_STR);
	$statement3->execute();
	
	
	// トランザクション完了（コミット）
	$pdo->commit();
		
	//データベース接続切断
	$dbh = null;
	
	//セッション変数を全て解除
	$_SESSION = array();
	
	//セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsesssionidを名前でやる
	if (isset($_COOKIE["PHPSESSID"])) {
    		setcookie("PHPSESSID", '', time() - 1800, '/');
	}
	
 	//セッションを破棄する
 	session_destroy();
 	
 	/*
 	登録完了のメールを送信
 	*/
	
}catch (PDOException $e){
	//トランザクション取り消し（ロールバック）
	$pdo->rollBack();
	$errors['error'] = "もう一度やりなおして下さい。";
	print('Error:'.$e->getMessage());
}

?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<!--<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ユーザー登録</title>
<link href="../css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="../style.css" rel="stylesheet" type="text/css">
<link href="../css/base.min.css" rel="stylesheet" type="text/css">
<link href="../css/sample.css" rel="stylesheet" type="text/css">

</head>-->

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ユーザー登録完了</title>
<link rel="stylesheet" href="../css/sign_up_success.css">
<script src="../js/jquery-2.1.1.min.js"></script>
<script src="../js/respond.min.js"></script>
<script src="../js/base.min.js"></script>
<script src="../js/project.min.js"></script>
    
    
    
  </head>

  <body>

   <aside class="profile-card">
  <header>
    <h1>SUCCESS!!</h1>
	<h2>登録完了しました。</h2>

  </header>

<div class="profile-bio">

    <p>ログイン画面から<br>再度ログインしてください。</p>

  </div>

 
  <div class="login_btn">
  <a href="../login_form.php">
    <button class="btn btn-red waves-attach waves-light" type="submit">ログインする </button>
    </a>
   </div>
</aside>
    
    
    
    
    
  </body>
</html>