<?php
if(isset($_POST['knopa']))
{
	echo "<pre>";
	print_r($_FILES);
	$tmp_name = $_FILES["img"]["tmp_name"];
	$name_unik=md5($tmp_name) . time();
	move_uploaded_file($tmp_name, "book_img/".$name_unik);
}
/*
$file = '/pub/Books/Specific/Fen-Sui.dla.Scastya.i.Udachi.Kalendar.2007.rar';
$ftp_server = 'ftp.stream.uz';
$ftp_user_name = 'anonymous';
$ftp_user_pass = '';

// ���ановка �оединени�
$conn_id = ftp_connect($ftp_server);

// п�ове�ка имени пол�зова�ел� и па�ол�
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// пол��ение �азме�а �айла $file
$res = ftp_size($conn_id, $file);

if ($res != -1) {
	echo 'Разме� �айла: ' . $res . 'бай�';
} else {
	echo "�е �дало�� оп�едели�� �азме� �айла";
}

// зак���ие �оединени�
ftp_close($conn_id);
*/

?> 
<form action="" enctype='multipart/form-data' method="POST">
<input id="file" type='file' name='img' size=35>
<input type="submit" name="knopa" value="1">
</form>
