<?php

mysql_connect('xxxxx', 'yyyyy','zzzzz') or die(mysql_error());
	/*
	xxxxx:MySQLのホスト
	yyyyy:MYSQLのユーザー名
	zzzzz:MySQLのパスワード
	*/
mysql_select_db('sssss');	//sssss:DB名
mysql_query('set character set utf8');

?>
