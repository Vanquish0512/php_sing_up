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

//前後にある半角全角スペースを削除する関数
function spaceTrim ($str) {
	// 行頭
	$str = preg_replace('/^[ 　]+/u', '', $str);
	// 末尾
	$str = preg_replace('/[ 　]+$/u', '', $str);
	return $str;
}

//エラーメッセージの初期化
$errors = array();

if(empty($_POST)) {
	header("Location: registration_mail_form.php");
	exit();
}else{
	//POSTされたデータを各変数に入れる
	$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : NULL;
	$password = isset($_POST['password']) ? $_POST['password'] : NULL;
	$wait = isset($_POST['wait']) ? $_POST['wait'] : NULL;
	$set_num = isset($_POST['set_num']) ? $_POST['set_num'] : NULL;
	$menu_1 = isset($_POST['menu_1']) ? $_POST['menu_1'] : NULL;
	$training_Goal1 = isset($_POST['training_Goal1']) ? $_POST['training_Goal2'] : NULL;
	$menu_2 = isset($_POST['menu_2']) ? $_POST['menu_2'] : NULL;
	$training_Goal2 = isset($_POST['training_Goal2']) ? $_POST['training_Goal2'] : NULL;
	$menu_3 = isset($_POST['menu_3']) ? $_POST['menu_3'] : NULL;
	$training_Goal3 = isset($_POST['training_Goal3']) ? $_POST['training_Goal3'] : NULL;
	$menu_4 = isset($_POST['menu_4']) ? $_POST['menu_4'] : NULL;
	$training_Goal4 = isset($_POST['training_Goal4']) ? $_POST['training_Goal4'] : NULL;
	$menu_5 = isset($_POST['menu_5']) ? $_POST['menu_5'] : NULL;
	$training_Goal5 = isset($_POST['training_Goal5']) ? $_POST['training_Goal5'] : NULL;
	$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : NULL;
	$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : NULL;
	
	echo $menu_4."<br />";
	echo $set_num."<br />";
	//前後にある半角全角スペースを削除
	$user_id = spaceTrim($user_id);
	$password = spaceTrim($password);
	$wait = spaceTrim($wait);
	$set_num = spaceTrim($set_num);
	$menu_1 = spaceTrim($menu_1);
	$training_Goal1 = spaceTrim($training_Goal1);
	$menu_2 = spaceTrim($menu_2);
	$training_Goal2 = spaceTrim($training_Goal2);
	$menu_3 = spaceTrim($menu_3);
	$training_Goal3 = spaceTrim($training_Goal3);
	$menu_4 = spaceTrim($menu_4);
	$training_Goal4 = spaceTrim($training_Goal4);
	$menu_5 = spaceTrim($menu_5);
	$training_Goal5 = spaceTrim($training_Goal5);
	$latitude = spaceTrim($latitude);
	$longitude = spaceTrim($longitude);
	
		//アカウント入力判定
	if ($user_id == ''):
		$errors['user_id'] = "アカウントが入力されていません。";
	elseif(mb_strlen($user_id)>10):
		$errors['user_id_length'] = "アカウントは10文字以内で入力して下さい。";
	endif;
	
	//パスワード入力判定
	if ($password == ''):
		$errors['password'] = "パスワードが入力されていません。";
	elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password"])):
		$errors['password_length'] = "パスワードは半角英数字の5文字以上30文字以下で入力して下さい。";
	else:
		$password_hide = str_repeat('*', strlen($password));
	endif;
	
}
$mail = $_SESSION['mail'];

//エラーが無ければセッションに登録
if(count($errors) === 0){
	$_SESSION['user_id'] = $user_id;
	$_SESSION['password'] = $password;
	$_SESSION['wait'] = $wait;
	$_SESSION['set_num'] = $set_num;
	$_SESSION['menu_1'] = $menu_1;
	$_SESSION['training_Goal1'] = $training_Goal1;
	$_SESSION['menu_2'] = $menu_2;
	$_SESSION['training_Goal2'] = $training_Goal2;
	$_SESSION['menu_3'] = $menu_3;
	$_SESSION['training_Goal3'] = $training_Goal3;
	$_SESSION['menu_4'] = $menu_3;
	$_SESSION['training_Goal4'] = $training_Goal3;
	$_SESSION['menu_5'] = $menu_3;
	$_SESSION['training_Goal5'] = $training_Goal3;
	$_SESSION['latitude'] = $latitude;
	$_SESSION['longitude'] = $longitude;
}

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
<title>Untitled Document</title>
<link href="../css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="../css/sign_style.css" rel="stylesheet" type="text/css">
<link href="../css/base.min.css" rel="stylesheet" type="text/css">
<link href="../css/material.css" rel="stylesheet" type="text/css">
<script src="../js/jquery-2.1.1.min.js"></script>
<script src="../js/respond.min.js"></script>
<script src="../js/base.min.js"></script>
<script src="../js/project.min.js"></script>
 <script type="text/javascript">
$(function(){
	set_num = <?= $set_num; ?>;
	
	console.log(set_num);
	  
    if (set_num == '4'){
	$('#menu_4').css('display','block');
	$('#training_Goal4').css('display','block');
	$('#menu_5').css('display','none');
	$('#training_Goal5').css('display','none');
	
	}else if (set_num == '5'){
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
<title>会員登録確認画面</title>
<meta charset="utf-8">
</head>
<body>
<div class="gridContainer clearfix">
  <div class="profile_sin_up_layout">
    <div class="sin_up_box profile_sin_up_back"></div>
    <div class="sin_up_box profile_sin_up_form">
      <h2>Confirmation
        <p class="profile_sin_up_form_p">項目確認</p>
      </h2>
      <?php if (count($errors) === 0): ?>
      <form action="registration_insert.php" method="post">
      <p class="title_p">LOGIN-ID</p>
      <p class="title_sub"><?=htmlspecialchars($user_id, ENT_QUOTES)?></p>
      
      <p class="title_p">Password</p>
      <p class="title_sub"><?=$password_hide?></p>
      
      <p class="title_p">現在の体重</p>
      <p class="title_sub"><?=htmlspecialchars($wait, ENT_QUOTES)?></p>
      
      <p class="title_p">メニュー項目数</p>
      <p class="title_sub"><?=htmlspecialchars($set_num, ENT_QUOTES)?></p>
       
      <p class="title_p">メニュー①</p>
      <p class="title_sub"><?=htmlspecialchars($menu_1, ENT_QUOTES)?></p>
      
      <p class="title_p">月の目標回数</p>
      <p class="title_sub"><?=htmlspecialchars($training_Goal1, ENT_QUOTES)."回"?></p>
      
      <p class="title_p">メニュー②</p>
      <p class="title_sub"><?=htmlspecialchars($menu_2, ENT_QUOTES)?></p>
      
      <p class="title_p">月の目標回数</p>
      <p class="title_sub"><?=htmlspecialchars($training_Goal2, ENT_QUOTES)."回"?></p>

      <p class="title_p">メニュー③</p>
      <p class="title_sub"><?=htmlspecialchars($menu_3, ENT_QUOTES)?></p>
      
      <p class="title_p">月の目標回数</p>
      <p class="title_sub"><?=htmlspecialchars($training_Goal3, ENT_QUOTES)."回"?></p>
      
      <p class="title_p">メニュー④</p>
      <p class="title_sub"><?=htmlspecialchars($menu_4, ENT_QUOTES)?></p>
      
      <p class="title_p">月の目標回数</p>
      <p class="title_sub"><?=htmlspecialchars($training_Goal4, ENT_QUOTES)."回"?></p>   
      
      <p class="title_p">メニュー⑤</p>
      <p class="title_sub"><?=htmlspecialchars($menu_5, ENT_QUOTES)?></p>
      
      <p class="title_p">月の目標回数</p>
      <p class="title_sub"><?=htmlspecialchars($training_Goal5, ENT_QUOTES)."回"?></p>         
      
      <div class="login_btn">
<input type="hidden" name="token" value="<?=$_POST['token']?>">
      
 		　<button class="btn btn-red waves-attach waves-light" type="button" onClick="history.back()"> 戻る </button>
          <button class="btn btn-red waves-attach waves-light" type="submit"> 登録する </button>
        </div>
           
<?php elseif(count($errors) > 0): ?>
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>  
   <button class="btn btn-red waves-attach waves-light" type="button" onClick="history.back()"> 戻る </button>
<?php endif; ?>           
      </form>
      
    </div>
  </div>
</div>
</body>
</html>

