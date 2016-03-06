<?php 
session_start();
require('../dbconnect.php');

if (!isset($_SESSION['join'])){
	header('Location: regist.php');
}

if(!empty($_POST)){
	//登録処理をする

	$sql = sprintf('INSERT INTO members SET username="%s", email="%s", password="%s", icon="%s",created="%s" ',
		mysql_real_escape_string($_SESSION['join']['username']),
		mysql_real_escape_string($_SESSION['join']['email']),
		sha1(mysql_real_escape_string($_SESSION['join']['password'])),
		mysql_real_escape_string($_SESSION['join']['image']),
		date('Y-m-d- H:i:s')
		);

	mysql_query($sql) or die(mysql_error());
	unset($_SESSION['join']);

	header('Location: ../index.php');
	}
?>


<!doctype>
<html lang="ja">

<head>

	<meta charset="utf-8">
	<title>登録内容確認｜My Checker</title>

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

	<h2>登録確認画面</h2>

	<div id="regist">

		<form action="" method="post">

			<input type="hidden" name="action" value="submit">

				<dl>
					<dt>ユーザネーム</dt>
					<dd><?php echo htmlspecialchars($_SESSION['join']['username'], ENT_QUOTES, 'UTF-8'); ?></dd>
					<dt>メールアドレス</dt>
					<dd><?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES, 'UTF-8'); ?></dd>
					<dt>パスワード</dt>
					<dd>【表示されません】</dd>
				</dl>

				<div class="submit"><a href="regist.php?action=rewrite">&laquo;&nbsp;書き直す</a>
				</div>
			<input type="submit" value="登録する" class="submit">

		</form>

	</div>

</body>

</html>