<?php header("Content-type:text/html; charset=koi8-r"); 
	require_once("inc.php");
	require_once("../functions.php");
	
	if (!connect()) die(1);
	
	switch($_GET['checked']) {
		case "add" : $onload = "javascript:add_stuff()"; break;
		case "change" : $onload = "javascript:change_stuff()"; break;
		case "del" : $onload = "javascript:del_stuff()"; break;
		default: $onload = "javascript:add_stuff()";
	}
	
	if (is_numeric($_GET['id'])) {
	if (! ($query = mysql_query("SELECT * FROM authors WHERE id={$_GET['id']}")) )
		die("Cannot execute a query: " . mysql_error());
		$author = mysql_fetch_assoc($query);	
	}
	
?>
<html>
	<head>
		<title> Adminka	(authors) </title>
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
          <a href="javascript:go('logout.php')" >Выход</a>
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
	
	<table width="100%" border='1'>
		<tr>
			<td width="50%">
	<form id="form_add_author" name="add_author" action="author.php" enctype='multipart/form-data' method="POST">
		РУС: ФИО автора, заполняется в виде <br>Фамилия, Имя Отчество:<br>
		<input type="text" name="name_ru" value="<?= isset($author)?htmlspecialchars($author['name_ru']):''; ?> " size='40'><br>
		<br>
		РУС: Биография автора: (не больше 65536 знаков)<br>
		<textarea name='biography_ru' cols=55 rows=15><?= isset($author)?htmlspecialchars($author['biography_ru']):'' ?></textarea><br>
		<br>
                
                УЗБ: ФИО автора, заполняется в виде <br>Фамилия, Имя Отчество:<br>
		<input type="text" name="name_uz" value="<?= isset($author)?htmlspecialchars($author['name_uz']):''; ?> " size='40'><br>
		<br>
		УЗБ: Биография автора: (не больше 65536 знаков)<br>
		<textarea name='biography_uz' cols=55 rows=15><?= isset($author)?htmlspecialchars($author['biography_uz']):'' ?></textarea><br>
		<br>
                
		Фото автора:&nbsp;(чтобы НЕ ИЗМЕНЯТЬ фото, оставьте это поле пустым!)<br>
		<input id='file' type='file' name='photo' size=35>&nbsp;&nbsp;
		<input id='def' type='checkbox' name='default' onchange='javascript:f=document.getElementById("file"); f.disabled=!f.disabled;'> дефолт :)
		<br><br>	
		<input type="submit" value="Пошёл">
		<input id="hidden_add" type="hidden" name="action" value="add">
		<input id="auth_id" type="hidden" name="auth_id" value='<?=isset($author)?$author['id']:"" ?>'>
	</form>
			</td>
			<td valign="top">
	<form id="form_del_author" name="del_author" action="author.php" method="POST">
		<select name='author_id' onchange='getData(this);' onmove="javascript:this.focus();">
        <?php
        if (is_numeric($_GET['id'])) $id = $_GET['id'];
				else $id = 1;
	      $query = mysql_query(
	        "SELECT *, name_ru AS name FROM authors ORDER BY name");
	      if (!$query) echo mysql_error();
	      while ($row = mysql_fetch_assoc($query)) {
	        //$row['name'] = htmlspecialchars($row['name']);
	        if ($id == $row['id']) $sel='selected';
	        	else $sel='';
	        echo "<option $sel value='{$row['id']}'>{$row['name']}</option>";
	        echo "\n";
	      }
	    ?>
		</select>&nbsp;&nbsp;
		<input id="del_button" type="button" value="Пошёл" onclick='check_form("del_author")'>
		<input type="hidden" name="action" value="del">
	</form><br>
    <font color=red>
      <b><center>ОСТОРОЖНО!!! БУДУТ УДАЛЕНЫ ВСЕ КНИГИ ДАННОГО АВТОРА!!!<br></center></b>
	</font>
		  </td>
		  </tr>
		</table>
	</body>
</html>
