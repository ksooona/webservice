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

//メッセージがあってimageもあったら
if (isset($_POST['message']) && isset($_FILES['image'])) {
	//画像以外のデータかどうかを確認。
	$filename = $_FILES['image']['name'];
	var_dump($filename);
	if(!empty($filename)){
		$ext = substr($filename, -3);
		var_dump($ext);
		if($ext != 'jpg' && $ext != 'gif' && $ext != 'png'){
			$error['image'] = 'type';
			var_dump($error);
		}
	}
}

//エラーがなかったら
if (empty($error)) {
	# 画像をアップロードする
	$image = date('YmdHis'). $_FILES['image']['name'];
	move_uploaded_file($_FILES['image']['tmp_name'], './member_picture/'. $image);

	$_SESSION['join']['image'] = $image;
}else{
//エラーがあったら
	header('Location: index.php');
	exit;

}

//投稿を記録する
if (!empty($_POST)){
	if ($_POST['message'] != ''){
		$sql = sprintf('INSERT INTO posts SET member_id=%d, message = "%s", reply_post_id=%d, picture="%s", created = NOW()',
		mysql_real_escape_string($member['id']),
		mysql_real_escape_string($_POST['message']),
		mysql_real_escape_string($_POST['reply_post_id']),
		mysql_real_escape_string($_SESSION['join']['image'])
		);

		mysql_query($sql) or die(mysql_error());
		header('Location: index.php');
		exit;
	}
}

//投稿を取得
$sql = sprintf('SELECT m.username, m.icon, p.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');
$posts=mysql_query($sql) or die(mysql_error());

//返信する
if (isset($_REQUEST['res'])){
	$sql = sprintf('SELECT m.username, m.icon,p.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=%d ORDER BY p.created DESC',
		mysql_real_escape_string($_REQUEST['res'])
		);

	$record = mysql_query($sql) or die(mysql_error());
	$table = mysql_fetch_assoc($record);
	$message = '>>>@' . $table['username'] . '' . $table['message'];
}

//いいねボタン
//var_dump($_REQUEST['fav']);
if (isset($_REQUEST['fav'])){
	var_dump($_REQUEST['fav']);
	$sql = sprintf('UPDATE posts SET favorite = favorite + 1 WHERE id = %d',
		mysql_real_escape_string($_REQUEST['fav'])
		);
	var_dump($sql);
	$record = mysql_query($sql) or die(mysql_error());
	var_dump($record);
	header('Location: index.php');
}

//goodボタン
if (isset($_REQUEST['god'])){
	var_dump($_REQUEST['god']);
	$sql = sprintf('UPDATE posts SET good = good + 1 WHERE id = %d',
		mysql_real_escape_string($_REQUEST['god'])
		);
	var_dump($sql);
	$record = mysql_query($sql) or die(mysql_error());
	var_dump($record);
	header('Location: index.php');
}

//coolボタン
if (isset($_REQUEST['col'])){
	var_dump($_REQUEST['col']);
	$sql = sprintf('UPDATE posts SET cool = cool + 1 WHERE id = %d',
		mysql_real_escape_string($_REQUEST['col'])
		);
	var_dump($sql);
	$record = mysql_query($sql) or die(mysql_error());
	var_dump($record);
	header('Location: index.php');
}

//surpriseボタン
if (isset($_REQUEST['sur'])){
	var_dump($_REQUEST['sur']);
	$sql = sprintf('UPDATE posts SET surprise = surprise + 1 WHERE id = %d',
		mysql_real_escape_string($_REQUEST['sur'])
		);
	var_dump($sql);
	$record = mysql_query($sql) or die(mysql_error());
	var_dump($record);
	header('Location: index.php');
}

?>

<!doctype>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>タイムライン｜My Checker</title>

</head>

<body>

<div id="header">

	<h1>My Checker</h1>

	<ul class="menu">
	<li><a href="mypage.php">マイページ</a></li>
	<li><a href="logout.php">ログアウト</a></li>
	</ul>

</div><!-- header -->

<div id="content">

	<ul>
	<a href="index.php"><li>Time Line</li></a>
	<a href="mypage.php"><li>My Page</li></a>
	</ul>

	<!-- メッセージ投稿 -->
	<form action="" enctype ="multipart/form-data" method="post" class="post">

		<dl>
			<dt>投稿する</dt>
				<dd><textarea name="message" cols="50" rows="5"><?php echo htmlspecialchars($message, ENT_QUOTES, 'utf-8');?></textarea>
					<input type="hidden" name="reply_post_id" value="<?php echo htmlspecialchars($_REQUEST['res'], ENT_QUOTES, 'utf-8'); ?>" />
				</dd>
		</dl>

		<input type="file" name="image" size="35" value="<?php echo htmlspecialchars($_POST['image'], ENT_QUOTES, 'utf-8'); ?>" class="submit">
			<?php if($error['image'] == 'type'): ?>
				<p>写真はgif,jpg,pngの画像を指定してください</p>
			<?php endif; ?>
			
		<input type="submit" value="投稿する" class="submit">

	</form>

	<!-- タイムライン -->
	<div id="timeline">

		<?php
		while ($post = mysql_fetch_assoc($posts)):
		?>

		<div id="head">
			<img src="member_icon/<?php echo htmlspecialchars($post['icon'], ENT_QUOTES, 'utf-8'); ?>" width="45" height="45" alt="<?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'utf-8'); ?>" >
				 <?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'utf-8'); ?>
				 (<a href="view.php?id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES, 'utf-8'); ?>"><?php echo htmlspecialchars($post['created'], ENT_QUOTES, 'utf-8'); ?></a>)<br>
		</div><!-- head -->

			<p><!-- messeage -->
				<span class="messeage"><?php echo htmlspecialchars($post['message'], ENT_QUOTES, 'utf-8'); ?></span><br>
				<img src="./member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES, 'utf-8'); ?>" width="480px" height="430px" />
			</p>

				<div id="button">
					<!-- リプライ -->
					<a href="index.php?res=<?php echo htmlspecialchars($post['id'], ENT_QUOTES, 'utf-8'); ?>"><img src="button/rep.png"></a>

					<!-- ふぁぼ -->
					<a href="index.php?fav=<?php echo htmlspecialchars($post['id'], ENT_QUOTES, 'utf-8'); ?>"><img src="button/favorite.png"></a>
					<?php if ($post['favorite'] > 0): ?>
						<?php echo htmlspecialchars($post['favorite'], ENT_QUOTES, 'utf-8'); ?>
					<?php endif; ?>

					<!-- good -->
					<a href="index.php?god=<?php echo htmlspecialchars($post['id'], ENT_QUOTES, 'utf-8'); ?>"><img src="button/good.png"></a>
					<?php if ($post['good'] > 0): ?>
						<?php echo htmlspecialchars($post['good'], ENT_QUOTES, 'utf-8'); ?>
					<?php endif; ?>

					<!-- good -->
					<a href="index.php?col=<?php echo htmlspecialchars($post['id'], ENT_QUOTES, 'utf-8'); ?>"><img src="button/cool.png"></a>
					<?php if ($post['cool'] > 0): ?>
						<?php echo htmlspecialchars($post['cool'], ENT_QUOTES, 'utf-8'); ?>
					<?php endif; ?>

					<!-- surprise -->
					<a href="index.php?sur=<?php echo htmlspecialchars($post['id'], ENT_QUOTES, 'utf-8'); ?>"><img src="button/surprise.png"></a>
					<?php if ($post['surprise'] > 0): ?>
						<?php echo htmlspecialchars($post['surprise'], ENT_QUOTES, 'utf-8'); ?>
					<?php endif; ?>
				</div><!-- button -->

			<p>	<!-- 返信元のメッセージ -->
				<?php if ($post['reply_post_id'] > 0): ?>
				<span class="res"><a href="view.php?id=<?php echo htmlspecialchars($post['reply_post_id'], ENT_QUOTES, 'utf-8'); ?>">返信元のメッセージ</a></span>
				<?php endif; ?>

				<p class="under_line"></p>
			</p>

		<?php endwhile; ?>

	</div><!-- timeline -->

</div><!-- content -->

</body>
</html>