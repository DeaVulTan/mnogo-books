<?php
    require_once('functions.php');
    error_reporting(1);
	
    if (!connect()) die();
    
    if (!is_numeric($_GET['id'])) error();
    $id = $_GET['id'];
    $query = mysql_query("SELECT * FROM books WHERE id=$id");
    if (!mysql_num_rows($query)) error();
    
    $row = mysql_fetch_assoc($query);
	
	$today = date("Y-m-d", mktime(0, 0, 0));
		
	if ( $row["today"] != $today )
	  $row["load_today"]=0;
		
	$row["load_today"]++; $row["load_total"]++;
//added by freddy	
	$sql = "SELECT COUNT(*) FROM download WHERE ip = '{$_SERVER['REMOTE_ADDR']}' AND book_id = $trackid";
	$sql_result = mysql_query($sql);
	$num = (mysql_fetch_row($sql_result));
	if ( $num[0] == 0 ) {
		$sql = "INSERT INTO download(book_id,ip,date,user_agent,hostname) VALUES({$row['id']},'{$_SERVER['REMOTE_ADDR']}',NOW(),'{$_SERVER['HTTP_USER_AGENT']}','{$_SERVER['REMOTE_HOST']}')";
		mysql_query($sql) or die("insert error");
		$sql = "DELETE FROM download WHERE TO_DAYS(NOW()) - TO_DAYS(date) >= 30";
		mysql_query($sql) or die("delete error");
	}
//----
	mysql_query("UPDATE books SET load_today={$row['load_today']}, load_total={$row['load_total']}, today=now() WHERE id={$id}") or die("Error: update");
	$link = rawurlencode($row['uri']);
	$link = str_replace("%3A", ":", $link);
	$link = str_replace("%2F", "/", $link);
	$row['uri'] = $link;
		
	$parsed = parse_url($row['uri']);
	$name = basename($parsed['path']);
	
	header("Content-type: application/octet-stream");
	header("Content-disposition: attachment; filename=$name");
    header("Location: ".stripslashes($row['uri']));
?>
