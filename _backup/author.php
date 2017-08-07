<?php
  require_once("functions.php");

  if (!connect()) die(1);

  $id = $_GET['author'];
  if (!is_numeric($id)) error();
	
  $query = mysql_query("SELECT * FROM authors WHERE id = $id")
	or die("Error: SELECT");
  if (!mysql_num_rows($query)) error(); // нет автора с данным id
	
  $author = mysql_fetch_assoc($query);
  $sql = "SELECT books.*,genre.name AS genre_name 
			FROM books,genre WHERE auth_id = {$author['id']} AND books.genre=genre.id ORDER BY genre_name, name";
  $query = mysql_query($sql) or die("Error: SELECT");
  if (mysql_num_rows($query)) { // если есть книги
	$i = 0;
    while ($row = mysql_fetch_assoc($query)) {
	  $his_books["{$row['genre_name']}_$i"] = $row;
	  $i++;
	}
	$show_books = <<<EOD
	<table class="books_table" border="0" cellpadding="2" width="100%">
EOD;
	$i = 0;
	$size = get_size($his_books);

	foreach ($his_books as $book) {
	  if ($book['genre_name']!=$prev_genre) {
		$show_books .= <<<EOD
		  <tr>
			<td class="designth" align="center" valign="center" colspan='3'>
			  <h3>{$book['genre_name']}</h3>
			</td>
		  </tr>
EOD;
	  }
	  $i++;
	  $show_books.= '<tr class="design'. (($i % 2) + 1) . 'td">' . "\n";
	  $show_books.= "<td><a href='book.php?id={$book['id']}'>{$book['name']}</a></td>\n";
	  $show_books.="<td align='center' width='15%'>" . date("d.m.Y", strtotime($book['date'])) . "</td>\n";
	  $show_books.="<td align='center' width='15%'>" . $size[$book['id']] . "</td>\n";
	  $show_books.="</tr>\n";
	  $prev_genre = $book['genre_name'];
	}
  $show_books.="</table>\n";
  }
  else $show_books = "<center>$no_books</center>";
  

$body = print_title($author['name']);
$body .= <<<EOD
<table width="100%" style="margin-right:2px" border='0'>
  <tr>
	<td align='center'><h3>{$lang['abstract']}</h3></td>
  </tr>
  <tr>
	<td valign='top' align='justify'>
	  <img class='contentImageLeft' src='auth_img/{$author['photo']}'>
	  <div class="annotation">{$author['biography']}</div>
	</td>
  </tr>
</table><br>
$show_books
EOD;

  require_once('templates/template.html')
?>
