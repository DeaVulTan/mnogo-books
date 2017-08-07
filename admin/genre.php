<?php 
	header("Content-type:text/html; charset=koi8-r"); 
	require_once("inc.php");
	require_once("../functions.php");
	if (!connect()) die("Can't connect to database!!!");

	switch($_POST['action']) {
		case "add" : add_genre(); break;
		case "change" : change_genre(); break;
		case "del": del_genre(); break;	
	}

			/*  Добавление жанра */

function add_genre() {

	if ( ($result =  mysql_query(
	"INSERT INTO genre (par_id, name_ru, name_uz) VALUES  ('-1','{$_POST['name_ru']}','{$_POST['name_uz']}')")) ) {
?>
<script>
		alert('Жанр был успешно добавлен в базу.');
		location.href = 'genre_form.php?checked=add';
</script>
<?php
	}
	else	die("Cannot execute a query: " . mysql_error());
}

			/*  Изменение жанра */

function change_genre() {

	if ( ($result =  mysql_query(
	"UPDATE genre SET name_ru='{$_POST['name_ru']}', name_uz='{$_POST['name_uz']}' WHERE id={$_POST['genre_id']}")) ) {
?>
<script>
		alert('Жанр был успешно обновлен.');
		location.href = 'genre_form.php?checked=change';
</script>
<?php
	}
	else	die("Cannot execute a query: " . mysql_error());
}

			/*  Удаление  жанра */

function del_genre() {

	$query = mysql_query("SELECT id,img FROM books WHERE genre={$_POST['genre_id']}");
	if (!$query) die("Cannot execute a query: " . mysql_error());
	if (mysql_num_rows($query)) {
		
		  // удаляем все книги данного жанра
	 
		while ($row = mysql_fetch_assoc($query)) {
			if ( !($result = mysql_query("DELETE FROM books WHERE id={$row['id']}")) )
				die("Cannot execute a query: " . mysql_error());
			$img = "../book_img/" . $row['img'];
			if (file_exists($img) && $row['img']!='default_book.png' && $row['img']) {
				if (!is_writable($img))
					die("Недостаточно прав для удаления файла: $img");
				if (!unlink($img))
					die("Не удалось удалить файл: $img");
			}
		}	// while
	}	// if (mysql_num_rows())
	 
	if (!($result = mysql_query("DELETE FROM genre WHERE id={$_POST['genre_id']}")) )
		die("Cannot execute a query: " . mysql_error());
?>
<script>
		alert('Все книги данного жанра были успешно удалены из базы вместе с жанром :)');
		location.href = 'genre_form.php?checked=del';
</script>	   
<?php } ?>
