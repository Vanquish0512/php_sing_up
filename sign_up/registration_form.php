<?php
session_start();

header("Content-type: text/html; charset=utf-8");

//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//データベース接続
require_once("../commons.php");
$pdo = db_connect();

//エラーメッセージの初期化
$errors = array();

if(empty($_GET)) {
	header("Location: registration_mail_form.php");
	exit();
}else{
	//GETデータを変数に入れる
	$urltoken = isset($_GET[urltoken]) ? $_GET[urltoken] : NULL;
	//メール入力判定
	if ($urltoken == ''){
		$errors['urltoken'] = "もう一度登録をやりなおして下さい。";
	}else{
		try{
			//例外処理を投げる（スロー）ようにする
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//flagが0の未登録者・仮登録日から24時間以内
			$statement = $pdo->prepare("SELECT mail FROM pre_member WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
			$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$statement->execute();
			
			//レコード件数取得
			$row_count = $statement->rowCount();
			
			//24時間以内に仮登録され、本登録されていないトークンの場合
			if( $row_count ==1){
				$mail_array = $statement->fetch();
				$mail = $mail_array[mail];
				$_SESSION['mail'] = $mail;
			}else{
				$errors['urltoken_timeover'] = "このURLはご利用できません。有効期限が過ぎた等の問題があります。もう一度登録をやりなおして下さい。";
			}
			
			//データベース接続切断
			$pdo = null;
			
		}catch (PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}
	}
}
$_SESSION['mail'] = $mail;
?>

<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ユーザー登録</title>
<link href="../css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="../css/sign_style.css" rel="stylesheet" type="text/css">
<link href="../css/base.min.css" rel="stylesheet" type="text/css">
<link href="../css/material.css" rel="stylesheet" type="text/css">
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&language=ja"></script>
<script src="../js/respond.min.js"></script>
<script src="../js/base.min.js"></script>
<script src="../js/project.min.js"></script>
<script src="../js/sin_up_map.js"></script>
 <script type="text/javascript">
$(function(){

  $('#tr_type select[name="set_num"]').change(function() {
	  
    if ($('select[name="set_num"] option:selected').val() == '4'){
	$('#menu_4').css('display','block');
	$('#training_Goal4').css('display','block');
	$('#menu_5').css('display','none');
	$('#training_Goal5').css('display','none');
	
	}else if($('select[name="set_num"] option:selected').val() == '5'){
	$('#menu_4').css('display','block');
	$('#training_Goal4').css('display','block');
	$('#menu_5').css('display','block');
	$('#training_Goal5').css('display','block');
	}else{
	$('#menu_4').css('display','none');
	$('#training_Goal4').css('display','none');
	$('#menu_5').css('display','none');
	$('#training_Goal5').css('display','none');
	}

	
  });
});
	</script>
</head>
<body onload="initialize()">
<div class="gridContainer clearfix">
<div class="profile_sin_up_layout">
<div class="sin_up_card">
<div class="sin_up_box profile_sin_up_back"></div>
<div class="sin_up_box profile_sin_up_form">
<h2>Registration
  <p class="profile_sin_up_form_p">詳細登録</p>
</h2>
<?php if (count($errors) === 0): ?>
<form action="registration_check.php" method="post">
  <div class="form_no_wrapper">
    <div class="form-group form-group-label form-group-green">
      <label class="floating-label" id="font_pre_input_label">LOGIN-ID</label>
      <input class="form-control"  type="text" name="user_id" id="font_pre_input_text">
      
    </div>
  </div>
  <div class="form_no_wrapper">
    <div class="form-group form-group-label form-group-green">
      <label class="floating-label" id="font_pre_input_label"> Password </label>
      <input class="form-control"  type="password" name="password" id="font_pre_input_text">
    </div>
  </div>
  <div class="form_no_wrapper">
    <div class="form-group form-group-label form-group-green">
      <label class="floating-label" id="font_pre_input_label">現在の体重</label>
      <input class="form-control"  type="text" name="wait" id="font_pre_input_text">
    </div>
  </div>
  <div class="form_no_wrapper" id="tr_type">
    <div class="form-group form-group-label form-group-green">
      <label class="floating-label" id="font_pre_input_label">トレーニング登録項目数</label>
      <select class="form-control" id="font_pre_input_label" name="set_num">
        <option></option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
    </div>
  </div>
  <div class="form_wrapper">
    <div class="form-group form-group-label form-group-green">
      <label class="floating-label" id="font_pre_input_label">メニュー①</label>
      <input class="form-control"  type="text" name="menu_1" id="font_pre_input_text">
    </div>
  </div>
  <div class="form_wrapper">
    <div class="form-group form-group-label form-group-green">
      <label class="floating-label" id="font_pre_input_label">メニュー①　月間目標回数</label>
      <input class="form-control"  type="text" name="training_Goal1" id="font_pre_input_text">
    </div>
  </div>
  <div class="form_wrapper">
    <div class="form-group form-group-label form-group-green">
      <label class="floating-label" id="font_pre_input_label">メニュー②</label>
      <input class="form-control"  type="text" name="menu_2" id="font_pre_input_text">
    </div>
  </div>
  <div class="form_wrapper">
    <div class="form-group form-group-label form-group-green">
      <label class="floating-label" id="font_pre_input_label">メニュー②　月間目標回数</label>
      <input class="form-control"  type="text" name="training_Goal2" id="font_pre_input_text">
    </div>
  </div>
  <div class="form_wrapper">
    <div class="form-group form-group-label form-group-green">
      <label class="floating-label" id="font_pre_input_label">メニュー③</label>
      <input class="form-control"  type="text" name="menu_3" id="font_pre_input_text">
    </div>
  </div>
  <div class="form_wrapper">
    <div class="form-group form-group-label form-group-green">
      <label class="floating-label" id="font_pre_input_label">メニュー③　月間目標回数</label>
      <input class="form-control"  type="text" name="training_Goal3" id="font_pre_input_text">
    </div>
  </div>
  
          <div class="form_wrapper" id="menu_4">
        <div class="form-group form-group-label form-group-green">
          <label class="floating-label" id="font_pre_input_label">メニュー④</label>
          <input class="form-control"  type="text" name="menu_4" id="font_pre_input_text">
        </div>
        </div>
        
         <div class="form_wrapper" id="training_Goal4">
        <div class="form-group form-group-label form-group-green">
          <label class="floating-label" id="font_pre_input_label">メニュー④ 月間目標回数</label>
          <input class="form-control"  type="text" name="training_Goal4" id="font_pre_input_text">
        </div>
         </div> 
         
         
        <div class="form_wrapper" id="menu_5">
        <div class="form-group form-group-label form-group-green">
          <label class="floating-label" id="font_pre_input_label">メニュー⑤</label>
          <input class="form-control"  type="text" name="menu_5" id="font_pre_input_text">
        </div>
        </div>
        
         <div class="form_wrapper" id="training_Goal5">
        <div class="form-group form-group-label form-group-green">
          <label class="floating-label" id="font_pre_input_label">メニュー⑤ 月間目標回数</label>
          <input class="form-control"  type="text" name="training_Goal5" id="font_pre_input_text">
        </div>
         </div>
  
  <h2>ランニングスタート地点<br>
    <p>スタート地点を設定してください。</p>
  </h2>
  <div class="gmap_box">
    <div id="map_canvas"></div>
    <input type="hidden" id="latitude" name="latitude" size="22" />
    <input type="hidden" id="longitude" name="longitude" size="22" />
  </div>
  <div class="login_btn">
    <input type="hidden" name="token" value="<?=$token?>">
    <button class="btn btn-red waves-attach waves-light" type="submit"> 登録する </button>
  </div>
</form>
<?php elseif(count($errors) > 0): ?>
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
<?php endif; ?>
</body>
</html>