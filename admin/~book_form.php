<?php header("Content-type:text/html; charset=koi8-r"); 
	require_once("inc.php");
	require_once("../functions.php");
	
// коннект
	if (!connect()) die(1);
	if (! ($result = mysql_query("SELECT * FROM authors ORDER BY name")) )
		die("Cannot execute a query: " . mysql_error());

	switch($_GET['checked']) {
		case "add" : $onload = "javascript:add_stuff()"; break;
		case "change" : $onload = "javascript:change_stuff()"; break;
		case "del" : $onload = "javascript:del_stuff()"; break;
		default: $onload = "javascript:add_stuff()";
	}
	
	if (is_numeric($_GET['id'])) {
	if (! ($query = mysql_query("SELECT * FROM books WHERE id={$_GET['id']}")) )
		die("Cannot execute a query: " . mysql_error());
		$book = mysql_fetch_assoc($query);	
	}


?>
<html>
	<head>
		<title> Adminka	(books)</title>
		<script type='text/javascript' src='javascript_voodoo.js'></script>
		 <link rel="Stylesheet" type="text/css" href='../styles/admin.css'>
	</head>
	<body onLoad="<?= $onload ?>">
	<table width="100%">
	  <tr>
		<td>
		  <a href="author_form.php">Автор</a>&nbsp;&nbsp;&nbsp;&nbsp;
		  <a href="book_form.php">Книга</a>&nbsp;&nbsp;&nbsp;&nbsp;
		  <a href="genre_form.php">Жанр</a>
		</td>
		<td align="right">
			<a href="javascript:go('logout.php');">Выход</a>
		</td>
	  </tr>
	</table>
	<div>
		<?php 
			$actions = array('add' => '"javascript:add_stuff()">Добавить',
											 'change' => '"javascript:change_stuff()">Изменить',
											 'del' => '"javascript:del_stuff()">Удалить');
			foreach ($actions as $key => $value) {
				if (isset($_GET['checked']) && $key == $_GET['checked'] or (!isset($_GET['checked']) && $key == 'add')) $sel = 'checked';
				else $sel = '';
				echo "<input type='radio' name='action' $sel onfocus=" . $value;
			}
		?>
	</div>
	
	
	<table width='100%' border='1'>
	  <tr>
		<td valign="top">
	<form id='form_add_book' name='add_book' action="book.php" enctype='multipart/form-data' method="POST">
	Название книги (без кавычек):&nbsp;
	<input type="text" size='45' name="name" value='<?= isset($book)?htmlspecialchars($book['name']):"" ?>' ><br>
	<br>
	Аннотация к книге:<br>
	
	<textarea name='annotation' cols=55 rows=10><?= isset($book)?htmlspecialchars($book['annotation']):"" ?></textarea><br>
	<br>
		
	Ссылка на книгу:&nbsp;<br>
	<input type="text" name="uri" size='60' value='<?= isset($book)?$book['uri']:"http://book.stream.uz/Books/" ?>' size=55><br>
	<br>
	Закачка картинки:&nbsp;(чтобы НЕ ИЗМЕНЯТЬ картинку, оставьте это поле пустым!)<br>
	<input id="file" type='file' name='img' size=35>
	<input id="def" type="checkbox" name="default" onchange='javascript:f=document.getElementById("file"); f.disabled=!f.disabled;'><span style="font-size:12px;"> дефолт :)</span>
	<br><br>
	Выберите автора книги:&nbsp;
	<select name="auth_id"> 
	<?php
	
		while($row = mysql_fetch_assoc($result) )
		{
			if ($row['id']==$book['auth_id']) $sel = 'selected';
				else $sel='';
			$row['name'] = htmlspecialchars($row['name']);
	    echo "<option $sel value='" . $row["id"] . "'>" . $row["name"] . "</option>";
		}
	
	?>
	</select>
	<br><br>
	Тип файла: 
	<select name="type">
		<option value="">-- неизвестно --</option>
		<option value="txt" <?=$book["type"]=="txt"?"selected":""; ?>>txt</option>
		<option value="doc" <?=$book["type"]=="doc"?"selected":""; ?>>doc</option>
		<option value="pdf" <?=$book["type"]=="pdf"?"selected":""; ?>>pdf</option>
		<option value="djvu" <?=$book["type"]=="djvu"?"selected":""; ?>>djvu</option>
		<option value="mp3" <?=$book["type"]=="mp3"?"selected":""; ?>>mp3</option>
		<option value="prc" <?=$book["type"]=="prc"?"selected":""; ?>>prc</option>
		<option value="chm" <?=$book["type"]=="chm"?"selected":""; ?>>chm</option>
		<option value="cwa" <?=$book["type"]=="cwa"?"selected":""; ?>>cwa</option>
		<option value="exe" <?=$book["type"]=="exe"?"selected":""; ?>>exe</option>
		<option value="ibk" <?=$book["type"]=="ibk"?"selected":""; ?>>ibk</option>
		<option value="rtf" <?=$book["type"]=="rtf"?"selected":""; ?>>rtf</option>
        <option value="jar" <?=$book["type"]=="jar"?"selected":""; ?>>jar</option>
	</select>
	<br><br>
	Выберите жанр:
	<select name="genre">
	<?php
		if (! ($result = mysql_query("SELECT * FROM genre ORDER BY name")) )
			die("Cannot execute a query: " . mysql_error());
		
		while($row = mysql_fetch_assoc($result) )
		{
			if ($row['id']==$book['genre']) $sel = 'selected';
				else $sel='';
			$row['name'] = htmlspecialchars($row['name']);			
		  echo "<option $sel value='" . $row["id"] . "'>" . $row["name"] . "</option>";
		}
	?>
	</select>
	&nbsp;&nbsp;
	<input type="submit" value="Пошёл">
	<input id="hidden_add" type="hidden" name="action" value="add">
	<input id="book_id" type="hidden" name="book_id" value='<?=isset($book)?$book['id']:"";?>'>
	</form>
	</td>
	<td valign="top">
	  <form id='form_del_book' name='del_book' action='book.php' method="POST">
		<select id='book_list' name='book_id' size=25 onchange='getData(this);'">
		<?php
		  $query = mysql_query(
			"SELECT books.id AS b_id, books.name AS b_name,books.date as b_date, authors.name AS a_name FROM books, authors WHERE books.auth_id=authors.id ORDER BY books.name");
		  if (!$query) echo mysql_error();
			if (is_numeric($_GET['id'])) $id = $_GET['id'];
				else $id = 1;
			$i=0;
		  while ($row = mysql_fetch_assoc($query)) {
				$row['b_date'] = date("m.d.Y H:i:s", strtotime($row['b_date']));
				$row['b_name'] = htmlspecialchars($row['b_name']);
				$row['a_name'] = htmlspecialchars($row['a_name']);
				if ($id==$row['b_id']) $sel = "selected";  // autoselect first item
			  	else $sel = "";
				$row['b_name'] = strlen($row['b_name'])>55? substr($row['b_name'],0,55) . '...': $row['b_name'];
				$row['a_name'] = strlen($row['a_name'])>15? substr($row['a_name'],0,15) . '...': $row['a_name'];
//				echo "<option id='{$i}' $sel value='{$row['b_id']}'>&quot;{$row['b_name']}&quot; - {$row['a_name']} [{$row['b_date']}]</option>\n";
				echo "<option id='{$i}' $sel value='{$row['b_id']}'>&quot;{$row['b_name']}&quot; - {$row['a_name']}</option>\n";
				$i++;
		  }
		?>	
		</select>
		<br><br>
			<input id="del_button" type="button" value="Пошёл" onclick='check_form("del_book")'>
			<input id="hidden_del" type="hidden" name="action" value="del">
	  </form>
	</td>
	</table>
	</body>
</html>