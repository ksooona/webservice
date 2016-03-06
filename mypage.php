<?php

session_start();
require('dbconnect.php');

//ログインチェック	
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
	//ログインしている

	$_SESSION['time'] = time();
	$sql = sprintf('SELECT * FROM members WHERE id=%d',
		mysql_real_escape_string($_SESSION['id'])
		);
	$record = mysql_query($sql) or die(mysql_error());
	$member = mysql_fetch_assoc($record);

}else{
	//ログインしてない
	header('Location: login.php');
	exit;
}

//投稿を取得
$sql = sprintf('SELECT * FROM posts WHERE member_id = %d ORDER BY created DESC', $_SESSION['id']);

$posts=mysql_query($sql) or die(mysql_error());


?>


<!doctype>
<html lang="ja">

<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/style.css">
<title>マイページ｜My Checker</title>
</head>

<body>

<div id="header">

	<h1>My Checker</h1>

	<ul class="menu">
	<li><a href="logout.php">ログアウト</a></li>
	<li><a href="index.php">トップ</a></li>
	</ul>

</div><!-- header -->

<div id="content">

	<h2><?php echo $_SESSION['username'] ?>のマイページ</h2>

	<div id="timeline">

		<?php
		while ($post = mysql_fetch_assoc($posts)):
		?>

		<div id="uname">
				<?php echo $_SESSION['username'] ?> (<?php echo htmlspecialchars($post['created'], ENT_QUOTES, 'utf-8'); ?>)<br>
		</div><!-- head -->	
		
		<p>
			<span class="messeage"><?php echo htmlspecialchars($post['message'], ENT_QUOTES, 'utf-8'); ?></span><br>
			<img src="member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES, 'utf-8'); ?>" width="400px" height="290px" alt="<?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'utf-8'); ?>" >
			 <?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'utf-8'); ?><br>
			 <p class="under_line"></p>
		</p>

<?php endwhile; ?>
</div><!-- timeline -->

</div><!-- content -->
</body>

</html>
