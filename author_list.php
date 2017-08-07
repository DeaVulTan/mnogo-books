<?php
// список авторов по буквам либо по жанрам

require_once('functions.php');

	if (!connect()) die(1);

	unset($disclaimer);
/*
  echo "<pre>\n";
  print_r($_SERVER);
  die();
*/
	if (isset($_GET['bukva'])) {
          if (strlen($_GET['bukva']) == 1) {
              $bukva = $_GET['bukva'];
          } else {
              $bukva = iconv("UTF-8", "KOI8-R", $_GET['bukva']);
          }
          $bukva = preg_replace(array("/\"/", "/\&/", "/img/"), "", preg_replace("/%20/", " ", strip_tags($_GET['bukva'])));
          $bukva = substr($bukva, 0, 1);
	  $hidden = "name='bukva' value='$bukva'"; // для правильного заполнения формы выбора автор/книга
	  switch ($_GET['switch']) {
		case 0: { // авторы на данную букву
			$discl_cent = preg_replace("/\(\(LETTER\)\)/", $bukva, $lang['discl_authors']);
			$in_title = preg_replace("/\(\(LETTER\)\)/", $bukva, $lang['letter_authors']);
			$sql = "SELECT authors.id,authors.name_" . SelectedLanguage . " AS name,count(books.id) AS add_info FROM authors,books 
			  WHERE authors.name_" . SelectedLanguage . " REGEXP('^" . $bukva . "') AND books.auth_id=authors.id 
			  GROUP BY authors.id ORDER BY name";
			break;
		}				
		case 1: { // книги на данную букву
			$discl_cent = preg_replace("/\(\(LETTER\)\)/", $bukva, $lang['discl_books']);
			$in_title = preg_replace("/\(\(LETTER\)\)/", $bukva, $lang['letter_books']);;
			$sql = "SELECT books.id,books.name_" . SelectedLanguage . " AS name,books.auth_id,authors.name_" . SelectedLanguage . " AS add_info FROM authors,books 
			  WHERE books.name_" . SelectedLanguage . " REGEXP('^" . $bukva . "') AND books.auth_id=authors.id 
			  GROUP BY books.id ORDER BY name";
			break;
		}	  
	  }		// end switch
	  
	  $page_title = $in_title;
	  $query = mysql_query($sql) or error();
	  if (!$all_rows = mysql_num_rows($query)) {
		$disclaimer = preg_replace("/\(\(DISCL_PART\)\)/", $discl_cent, $lang['disclaimer']['1']);
	  }
	}
	elseif (isset($_GET['genre']) && is_numeric($_GET['genre'])) {
	  $genre = $_GET['genre'];
	  $hidden = "name='genre' value='$genre'"; // для правильного заполнения формы выбора автор/книга
	  switch ($_GET['switch']) {
		case 0: { // авторы данного жанра
			$discl_cent = $lang['discl_genre_authors'];
			$in_title = $lang['genre_authors'];
			$sql = "SELECT authors.id,authors.name_" . SelectedLanguage . " AS name,count(books.id) AS add_info FROM books,authors 
			  WHERE books.genre={$genre} AND authors.id=books.auth_id GROUP BY authors.id ORDER BY name";
			break;
		}				
		case 1: { // книги данного жанра
			$discl_cent = $lang['discl_genre_books'];
			$in_title = $lang['genre_books'];
			$sql = "SELECT books.id,books.name_" . SelectedLanguage . " AS name,books.auth_id,authors.name_" . SelectedLanguage . " AS add_info FROM books,authors 
			  WHERE books.genre={$genre} AND authors.id=books.auth_id GROUP BY books.id ORDER BY name";
			break;
		}
	  } 	// end switch
	  	  
	  $name_genre = get_genre_by_id($genre);
	  $page_title = "$in_title \"$name_genre\"";
	  $query = mysql_query($sql) or error();
	  if (!$all_rows = mysql_num_rows($query)) {
		$disclaimer = preg_replace("/\(\(DISCL_PART\)\)/", $discl_cent, $lang['disclaimer']['2']);
                $disclaimer = preg_replace("/\(\(GENRE_NAME\)\)/", $name_genre, $disclaimer);
	  }
	}
	
  $sel = $_GET['switch']? 'selected' : ''; // выбор правильного пункта в списке автор/книга
  
  if (isset($disclaimer)) $body = $disclaimer;
  else {
	$body = print_title($page_title);
	$body .= <<<EOD
	<div style="font-size: 13px; width='100%'; clear: both; margin-left: 10px; margin-bottom: 2px; margin-top: 5px;">
	  <form name='contSel' action='{$_SERVER["PHP_SELF"]}'>
		<select name='switch' class="contentFormSelect" onchange="javascript: document.forms['contSel'].submit();">
		  <option value='0'>{$lang['select']['author']}</option>
		  <option $sel value='1'>{$lang['select']['book']}</option>		  
		</select>
		<input type='hidden' $hidden>
	  </form>
	</div>
	  <table border='0' width="100%">
		<tr>
		  <td valign='top' width="45%">
			<ul>
EOD;
		$rows_per_column = round($all_rows / 2);
		$all_columns = 2;
		$i = -1;
		$col = 0;
		while ($row = mysql_fetch_assoc($query))  {
		  $i++;
		  if ($i == $rows_per_column) {	// новый столбец
			$i = 0;
			$body.= "</ul></td>\n";
			$col++;
			if ($col == $all_columns) { // новая строка
			  $col = 0;
			  $body.= "</tr>\n";
			  $body.= "<tr>\n";
/*			  $body.= "<td valign='top' colspan='$all_columns'><hr></td>";
			  $body.= "</tr><tr>\n";*/
			}
			$body.= "<td valign='top''>\n<ul>\n";
		  }
		  if ($_GET['switch']) { // выбраны книги
//			$row['add_info'] = ereg_replace(', [A-Za-z]' , '', $row['add_info']);
			$pos = strpos($row['add_info'],', ');
			if ($pos !==false)
			  $row['add_info'] = substr($row['add_info'],0,$pos);
			$body .="<li><a href='book.php?id={$row['id']}'>{$row['name']}</a>
			(<a href='author.php?author={$row['auth_id']}'>{$row['add_info']}</a>)";
		  } else
		  $body.= "<li><a href='author.php?author={$row['id']}'>{$row['name']} ({$row['add_info']})</a>";
		  $body.= "<br>\n";
		}
	$body .= <<<EOD
		  </ul>
		</td>
	  </tr>
	</table>
EOD;
  }
  require_once('templates/template.html');
?>
