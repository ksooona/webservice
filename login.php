<?php

require('dbconnect.php');

session_start();

if($_COOKIE['email'] != ''){
	$_POST['email'] = $_COOKIE['email'];
	$_POST['password'] = $_COOKIE['password'];
	$_POST['save'] = 'on';
}


if (!empty($_POST)) {
	// ログインの処理

	if ($_POST['email'] != '' && $_POST['password'] != '') {

		$sql = sprintf('SELECT * FROM members WHERE email="%s" AND password="%s"',
		mysql_real_escape_string($_POST['email']),
		sha1(mysql_real_escape_string($_POST['password']))
		);
		$record = mysql_query($sql) or die(mysql_error());

		if($table = mysql_fetch_assoc($record)){
			//ログイン成功
			$_SESSION['id'] = $table['id'];
			$_SESSION['username'] = $table['username'];
 			$_SESSION['time'] = time();

			//ログイン情報を記録
			if($_POST['save'] == 'on'){
				setcookie('email', $_POST['email'], time()+60*60*24*14);
				setcookie('password', $_POST['password'], time()+60*60*24*14);
			}
			
			header('Location: index.php');
		}else{
			$error['login'] = 'failed';
			}

		}else{
			$error['login'] = 'blank';
			}
	}
?>

<!doctype>

<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../css/style.css">

<title>ログイン｜My Checker</title>
</head>

<body>

<div id="header">

	<h1>My Checker</h1>

			<ul class="menu">
				<li><a href="join/regist.php">会員登録</a></li>
				<li><a href="login.php">ログイン</a></li>
			</ul>

</div><!-- header　-->

<h2>ログイン</h2>

<div id="regist">

	<form action="" method="post">
	<!-- ログイン -->
		<dl>
			<dt>メールアドレス</dt>
			<dd><input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email']); ?>" />
				<?php if ($error['login'] == 'blank'): ?>
				<p class="login_error">*メールアドレスとパスワードを入力してください</p>
				<?php endif; ?>
				<?php if($error['login'] == 'failed'): ?>
				<p class="login_error">*ログインに失敗しました。正しく入力してください</p>
				<?php endif; ?>
			</dd>
			<dt>パスワード</dt>
			<dd><input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password']); ?>" />
			</dd>
			<dt>ログイン情報の記録</dt>
			<dd><input id="save" type="checkbox" name="save" value="on"><label for="save">次回からは自動的にログインする</label></dd>
		</dl>

		<input type="submit" value="ログイン" class="submit">

	<!-- 会員登録 -->
	<h3>会員になっていない方はこちらから</h3>
	<p class="submit">&raquo;<a href="join/regist.php">会員登録</a></p>

		
	</form>

</div><!-- regist　-->

</body>
</html>
