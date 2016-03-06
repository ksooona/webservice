<?php
require('../dbconnect.php');

session_start();

if(!empty($_POST)){

	//エラー項目の確認

	if($_POST['username'] == ''){
		$error['username'] = 'blank';
	}
	if($_POST['email'] == ''){
		$error['email'] = 'blank';
	}
	if(strlen($_POST['password']) < 4 ){
		$error['password'] = 'length';
	}
	if($_POST['password'] == ''){
		$error['password'] = 'blank';
	}

	if(empty($error)){
	//重複アカウントのチェック
	$sql=sprintf('SELECT COUNT(*) As cnt FROM members WHERE email="%s"',
	mysql_real_escape_string($_POST['email'])
	);
	$record = mysql_query($sql) or die(mysql_error());
	$table = mysql_fetch_assoc($record);
	if ($table['cnt'] > 0) {
		$error['email'] = 'duplicate';
		}
 	}

 	if(empty($error)){

 		//画像アップロード
 		$image = date('YmdHis'). $_FILES['image']['name'];
 		move_uploaded_file($_FILES['image']['tmp_name'],'../member_icon/'.$image);

		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
	}
}

if($_REQUEST['action'] == 'rewrite'){
	//書き直し
	$_POST = $_SESSION['join'];
	$error['rewrite'] = true;
}
?>


<!doctype>

<html lang="ja">
<head>

<meta charset="utf-8">
<title>会員登録｜My Checker</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div id="header">

	<h1>My Checker</h1>

			<ul class="menu">
				<li><a href="regist.php">会員登録</a></li>
				<li><a href="../login.php">ログイン</a></li>
			</ul>

</div><!-- header　-->

<h2>My Checkerに会員登録</h2>

<div id="regist">

	<!-- 新規登録　-->
	<form method="post" action="" enctype="multipart/form-data">

		<dl>
			<dt>ユーザネーム</dt>
			<dd><input type="text" name="username" id="username" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>" >
				<?php if ($error['username'] == 'blank'): ?>
				<p class="regist_empty">※ユーザーネームを入力してください</p>
				<?php endif; ?>
			</dd>
			<dt>メールアドレス</dt>
			<dd><input type="text" name="email" id="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); ?>" />
				<?php if ($error['email'] == 'blank'): ?>
				<p class="regist_empty">※メールアドレスを入力してください</p>
				<?php endif; ?>
				<?php if ($error['email'] == 'duplicate'): ?>
				<p class="regist_empty">※指定されたメールアドレスはすでに登録されています</p>
				<?php endif; ?>
			</dd>
			<dt>パスワード</dt>
			<dd><input type="password" name="password" id="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); ?>" >
				<?php if ($error['password'] == 'blank'): ?>
				<p class="regist_empty">※パスワードを入力してください</p>
				<?php endif; ?>
				<?php if ($error['password'] == 'length'): ?>
				<p class="regist_empty">※パスワードは4文字以上で入力してください</p>
				<?php endif; ?>
			</dd>
			<dt>アイコン画像</dt>
			<dd>
				<input type="file" name="image" size="35">
			</dd>
		</dl>
		<input type="submit" value="登録" class="submit">

	</form>

</div><!-- regist -->

</body>
</html>
