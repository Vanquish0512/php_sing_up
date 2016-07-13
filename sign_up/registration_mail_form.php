<?php
session_start();

header("Content-type: text/html; charset=utf-8");

//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
?>
<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>メール登録画面</title>
<link href="../css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="../css/sign_style.css" rel="stylesheet" type="text/css">
<link href="../css/base.min.css" rel="stylesheet" type="text/css">
<link href="../css/material.css" rel="stylesheet" type="text/css">

<script src="../js/jquery-2.1.1.min.js"></script>
<script src="../js/respond.min.js"></script>
<script src="../js/base.min.js"></script>
<script src="../js/project.min.js"></script>

</head>
<body>

<div class="gridContainer clearfix">
  <div class="position_box pre_sing_up">
    <h1 class="pre_sing_title">Welcome</h1>
    <div class="pre_box">
    <form action="registration_mail_check.php" method="post">
      <h2 class="pre_sing_title2">メールアドレスの登録</h2>
      <div class="form-group form-group-label form-group-white">
        <label class="floating-label" id="font_pre_input_label"> MailAddress </label>
        <input class="form-control"  type="text" id="font_pre_input_text" name="mail">
      </div>
      <div class="login_btn">
      <input type="hidden" name="token" value="<?=$token?>">
        <button class="btn btn-red waves-attach waves-light" type="submit"> 登録する </button>
      </div>
      </form>
    </div>
  </div>
</div>
 
</body>
</html>