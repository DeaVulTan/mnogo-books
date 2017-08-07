<?php
	header("Content-type:text/html; charset=koi8-r"); 
	require_once("inc.php");
	require_once("../functions.php");
	
	if (!connect()) die("Can't connect to database!!!");
	
	
	switch($_POST['action']) {
		case "add" : add_book(); break;
		case "change" : change_book(); break;
		case "del": del_book(); break;	
	}

	/*      Добавление книги     */

function add_book() {
	$tmp_name = $_FILES["img"]["tmp_name"];
	if (!$tmp_name) $name_unik = 'default_book.png';
			else $name_unik=md5_file($tmp_name) . time();
	if ( ($result =  mysql_query(
	  "INSERT INTO books (auth_id, name_ru, name_uz, annotation_ru, annotation_uz, uri, genre, img, date, `type`) VALUES  ('{$_POST['auth_id']}','{$_POST['name_ru']}','{$_POST['name_uz']}','{$_POST['annotation_ru']}','{$_POST['annotation_uz']}','{$_POST['uri']}','{$_POST['genre']}', '{$name_unik}', now(), '{$_POST['type']}')")) ) 
	{
		if ($tmp_name) {
			if (!move_uploaded_file($tmp_name, "../book_img/".$name_unik)) 
				die("Не могу залить файл: ../book_img/" . $name_unik);
		}
?>
    <script>
	  alert('Книга была успешно добавлена в базу.');
	  location.href = 'book_form.php?checked=add';
    </script>
<?php
	  } // if ($result = ...)
	  else die("Cannot execute a query: " . mysql_error());
}
	
	/*      Изменение книги       */
	
function change_book() {
	if (!is_numeric($_POST['book_id']))  die("Fuck off!");
	$tmp_name = $_FILES["img"]["tmp_name"];
	$query = mysql_query("SELECT img FROM books WHERE id={$_POST['book_id']}");
	$row = mysql_fetch_assoc($query);
	$img = "../book_img/" . $row['img'];
	if ($tmp_name) { // меняем картинку
		$name_unik=md5_file($tmp_name) . time();	// новое имя файла
		if (file_exists($img) && $row['img']!='default_book.png' && $row['img']) {
			if (!is_writable($img)) 
				die("Недостаточно прав для удаления файла: $img");
			if (!unlink($img)) 
				die("Не могу удалить файл: $img");		
		}
		if (!move_uploaded_file($tmp_name, "../book_img/".$name_unik)) 
			die("Не могу залить файл: ../book_img/" . $name_unik);
	} elseif ($_POST['default']=='on') { // удаляем предыдущий файл и выставляем дефолт
	  $name_unik='default_book.png'; 
	  if (file_exists($img) && $row['img']!='default_book.png' && $row['img']) {
		if (!is_writable($img)) 
		  die("Недостаточно прав для удаления файла: $img");
		if (!unlink($img)) 
	  	  die("Не могу удалить файл: $img");		
		}
	}
	$ins_img = $name_unik? ", img='$name_unik'":"";
	if (mysql_query("UPDATE books SET type='{$_POST['type']}', name_ru='{$_POST['name_ru']}', name_uz='{$_POST['name_uz']}', auth_id={$_POST['auth_id']}, uri='{$_POST['uri']}', annotation_ru='{$_POST['annotation_ru']}', annotation_uz='{$_POST['annotation_uz']}', genre='{$_POST['genre']}' $ins_img WHERE id='{$_POST['book_id']}'")) {
?>
    <script>
	  alert('Книга была успешно обновлена.');
	  location.href = 'book_form.php?checked=change';
    </script>
<?php
	  } // if (mysql_query)
	  else die("Cannot execute a query: " . mysql_error());
}
	
	
	/*      Удаление книги       */
	
function del_book() {
	$query = mysql_query("SELECT img FROM books WHERE id={$_POST['book_id']}");
	if (!$query) 
		die("Cannot execute a query: " . mysql_error());
	$row = mysql_fetch_assoc($query);
	if ( !($result = mysql_query("DELETE FROM books WHERE id={$_POST['book_id']}")) )
		die("Cannot execute a query: " . mysql_error());
	$img = "../book_img/" . $row['img'];
	if (file_exists($img) && $row['img']!='default_book.png' && $row['img']) {
		if (!is_writable($img)) 
			die("Недостаточно прав для удаления файла: $img");
		if (!unlink($img)) 
			die("Не удалось удалить файл: $img");
	  }
?>
<script>
		alert('Книга была успешно удалена из базы.');
		location.href = 'book_form.php?checked=del';
</script>

<?php 
} 
?>


