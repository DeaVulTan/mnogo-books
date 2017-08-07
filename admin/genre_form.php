<?php header("Content-type:text/html; charset=koi8-r"); 
	require_once("inc.php");
	require_once("../functions.php");
	
// коннект
	if (!connect()) die(1);
	if (! ($result = mysql_query("SELECT *, name_ru AS name FROM authors ORDER BY name")) )
		die("Cannot execute a query: " . mysql_error());
	
	switch($_GET['checked']) {
		case "add" : $onload = "javascript:add_stuff()"; break;
		case "change" : $onload = "javascript:change_stuff()"; break;
		case "del" : $onload = "javascript:del_stuff()"; break;
		default: $onload = "javascript:add_stuff()";
	}
	
	if (is_numeric($_GET['id'])) {
	if (! ($query = mysql_query("SELECT * FROM genre WHERE id={$_GET['id']}")) )
		die("Cannot execute a query: " . mysql_error());
		$genre = mysql_fetch_assoc($query);	
	}
?>
<html>
	<head>
		<title> Adminka	(books)</title>
		<script type='text/javascript' src='javascript_voodoo.js'></script>
		<link rel="Stylesheet" type="text/css" href='../styles/admin.css'>
	</head>
	<body onload="<?= $onload ?>">
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
			<td valign="top" width="50%">
				<form name='add_genre' action="genre.php" method="POST">
					РУС: Название жанра:&nbsp;
					<input type="text" size='25' name="name_ru" value='<?= isset($genre)?$genre['name_ru']:"" ?>'><br />
                                        
                                        УЗБ: Название жанра:&nbsp;
                                        <input type="text" size='25' name="name_uz" value='<?= isset($genre)?$genre['name_uz']:"" ?>'><br />
                                        <br />
					<input id="add_button" type="submit" value="Пошёл">
					<input id="hidden_add" type="hidden" name="action" value="add">
					<input id="genre_id" type="hidden" name="genre_id" value='<?=isset($genre)?$genre['id']:""?>'>
				</form>
			</td>
			<td valign="top">
			<form name='del_genre' action='genre.php' method="POST">
				<select name='genre_id' onchange='getData(this);' onmove='javascript:this.focus();'>
				<?php
					if (is_numeric($_GET['id'])) $id = $_GET['id'];
						else $id = 1;
					$query = mysql_query("SELECT *, name_ru AS name FROM genre ORDER BY name");
					if (!$query) echo mysql_error();
					$i = true;
					while ($row = mysql_fetch_assoc($query)) {
						$row['name'] = htmlspecialchars($row['name']);
						if ($id == $row['id']) $sel='selected';
							else $sel='';
						echo "<option $sel value='{$row['id']}'>{$row['name']}</option>\n";
					}
				?>	
				</select>&nbsp;&nbsp;
				<input id="del_button" type="button" value="Пошёл" onclick='check_form("del_genre")'>
				<input id="hidden_del" type="hidden" name="action" value="del">
			</form><br>
	  	<font color=red>
	  	<b><center>ОСТОРОЖНО!!! БУДУТ УДАЛЕНЫ ВСЕ КНИГИ ДАННОГО ЖАНРА!!!<br></center> </b>
	  	</font >
			</td>
		</tr>
	</table>
	</body>
</html>