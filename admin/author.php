<?php 
	header("Content-type:text/html; charset=koi8-r"); 
	require_once("inc.php");
	require_once("../functions.php");
	
	if (!connect()) die("Can't connect to database!!!");
	
	switch($_POST['action']) {
		case "add" : add_author(); break;
		case "change" : change_author(); break;
		case "del": del_author(); break;	
	}

function add_author() {
	$tmp_name = $_FILES["photo"]["tmp_name"];
	if (!$tmp_name) $name_unik = 'default_author.png';
		else $name_unik=md5_file($tmp_name) . time();
	if ( ($result = mysql_query(
	"INSERT INTO authors (name_ru, name_uz, biography_ru, biography_uz, photo, date) VALUES ('{$_POST['name_ru']}','{$_POST['name_uz']}','{$_POST['biography_ru']}','{$_POST['biography_uz']}','{$name_unik}', now())")) ) 
	{
		if ($tmp_name) {
			if (!move_uploaded_file($tmp_name,"../auth_img/" . $name_unik)) 
				die("�� ���� ������ ����: ../auth_img/" . $name_unik);
		}
?>
    <script>
	  alert('����� ��� ������� ��������.');
	  location.href = 'author_form.php?checke=add';
    </script>
<?php
	  } // if ( $result = ...)	
	  else die("Cannot execute a query: " . mysql_error());
}

function change_author() {
	if (!is_numeric($_POST['auth_id']))  die("Fuck off!");
	$tmp_name = $_FILES["photo"]["tmp_name"];	
	if ($tmp_name) { // ������ ��������
		$query = mysql_query("SELECT photo FROM authors WHERE id={$_POST['auth_id']}");
		$row = mysql_fetch_assoc($query);
		$img = "../auth_img/" . $row['photo'];
		$name_unik=md5_file($tmp_name) . time();	// ����� ��� �����
		if (file_exists($img) && $row['photo']!='default_author.png' && $row['photo']) {
			if (!is_writable($img)) 
				die("������������ ���� ��� �������� �����: $img");
			if (!unlink($img)) 
				die("�� ���� ������� ����: $img");
		}
		if (!move_uploaded_file($tmp_name, "../auth_img/".$name_unik)) 
			die("�� ���� ������ ����: ../auth_img/" . $name_unik);
	} elseif ($_POST['default']=='on') { // ������� ���������� ���� � ���������� ������
	  $name_unik='default_author.png';
	  if (file_exists($img) && $row['photo']!='default_author.png' && $row['photo']) {
		if (!is_writable($img)) 
	  	  die("������������ ���� ��� �������� �����: $img");
		if (!unlink($img)) 
		  die("�� ���� ������� ����: $img");
	  }	
	}
	$ins_img = $name_unik?", photo='$name_unik'":"";
	if (mysql_query("UPDATE authors SET name_ru='{$_POST['name_ru']}', name_uz='{$_POST['name_uz']}', biography_ru='{$_POST['biography_ru']}', biography_uz='{$_POST['biography_uz']}' $ins_img WHERE id='{$_POST['auth_id']}'")) {
?>
    <script>
	  alert('������ ������ ���� ������� ���������.');
	  location.href = 'author_form.php?checked=change';
    </script>
<?php
	  } // if (mysql_query)
	  else die("Cannot execute a query: " . mysql_error());
}


function del_author() {

	if (!($query = mysql_query(
	"SELECT id,img FROM books WHERE auth_id={$_POST['author_id']}")) )
		die("Cannot execute a query: " . mysql_error());

	  // ������� ��� ����� ������� ������

	if (mysql_num_rows($query)) {
		while ( $row = mysql_fetch_assoc($query)) {
		if ( !($result = mysql_query("DELETE FROM books WHERE id={$row['id']}")) )
			die("Cannot execute a query: " . mysql_error());
		$cur_file = "../book_img/" . $row['img'];
		if (file_exists($cur_file) && $row['img']!='default_book.png') {
			if (!is_writable($cur_file))
				die("������������ ���� ��� �������� �����: $cur_file");
			if (!unlink($cur_file))
				die("�� ������� ������� ����: $cur_file");
			}
		} // while
	} // if mysql_num_rows()
	
	  // ������� ���� ������
	
	if (!($query = mysql_query("SELECT photo FROM authors WHERE id={$_POST['author_id']}")) )
		die("Cannot execute a query: " . mysql_error());
	$photo = mysql_fetch_row($query);
	$cur_file = "../auth_img/" . $photo[0];
  
	if (file_exists($cur_file) && $photo[0]!='default_author.png' && $photo[0]) {
		if (!is_writable($cur_file))
			die("������������ ���� ��� �������� �����: $cur_file");
		if (!unlink($cur_file))
			die("�� ������� ������� ����: $cur_file");
	}
	 
	if (!($result = mysql_query("DELETE FROM authors WHERE id={$_POST['author_id']}")) )
		die("Cannot execute a query: " . mysql_error());
?>
<script>
  alert('��� ����� ������� ������ ���� ������� ������� �� ���� ����� �� ������� :)');
  location.href = 'author_form.php?checked=del';
</script>

<?php } ?>
