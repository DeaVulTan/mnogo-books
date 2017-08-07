<?php
require_once("functions.php");

if (!connect()) die("");

//added by freddy
/*
$sql = "SELECT books.*,authors.name AS author,COUNT(download.id) AS sumdownload FROM books, download, authors WHERE books.id=download.book_id AND books.auth_id=authors.id AND (TO_DAYS(NOW()) - TO_DAYS(download.date) <= 7) GROUP BY books.id ORDER BY sumdownload DESC LIMIT 5";
	$books_result = mysql_query($sql);
	if( mysql_num_rows($books_result) ) {
		$body = print_title('Топ книг');
		$body.= "<br><table width='100%' class='books_table'>\n";	
		while ( $row=mysql_fetch_assoc($books_result) ) {
			$body .= <<<EOD
	<tr>
		<td colspan='2' ><a href='book.php?id={$row['id']}'>{$row['name']}</a> | <a href='author.php?author={$row['auth_id']}'>{$row['author']}</a></td>
	</tr>
	<tr valign="top">
	<td ><img width='50' height='75' src="thumbnail.php?f=book_img/{$row['img']}"></td>
	<td style="font-size:12px" align="justify" valign="top">
		<div class="annotation">{$row['annotation']}</div></td>
	</tr>
EOD;
		}
		$body .="</table>";
	}
 */
//----

$sql = "SELECT books.*,authors.name AS author,genre.name AS genre_name
  FROM books,authors,genre WHERE books.auth_id=authors.id AND books.genre=genre.id ORDER BY books.date DESC LIMIT 12";

$query = mysql_query($sql) or die("Error: SELECT");

if (!$num = mysql_num_rows($query)) { 
	$body = "Database is empty!"; 
	error();
}


$i=0; $books = array();
$prev_date = 0;
while($row = mysql_fetch_assoc($query)) { // разбивка новостей по разным дням
	$date = date("d.m.Y", strtotime($row['date']));
	if (($prev_date!=$date && $i)) { // сортировка по жанру в пределах одного дня
		krsort($data, SORT_STRING);
		$books = $data + $books; 
		$data = array();
	}
	$data["{$row['genre_name']}_{$i}"] = $row;
	$prev_date = $date;
	$i++;
}


	krsort($data, SORT_STRING);
	$books = $data + $books;
	$data = array();
/*
echo "<pre>\n";
print_r($books);
die();
*/

$body .= print_title($lang['last_added']);
$body.= "<br><table width='100%' class='books_table'>\n";
$prev_date = $prev_genre = 0;
while ($row = array_pop($books)) {
	$date = date("d.m.Y", strtotime($row['date']));
	
/* разбиение по переменным группируемых полей таблицы  */
	/* $date_row = <<<EOD
	<tr>
		<td class="designth" colspan='2' align='center'><h3>{$date}</h3></td>
	</tr>
EOD;*/
        $date_row = "";
	$genre_row = <<<EOD
	<tr>
		<td class="contentArticleLink designth" colspan='2' align='center'><h3><a href='author_list.php?genre={$row['genre']}'>{$row['genre_name']}</a></h3></td>
	</tr>
EOD;
	/* --------------- */
	
	if ($prev_date!=$date) {
		$body.=$prev_date?"</table>":""; // в первый раз не выводим </table>
		$body.="<table width='100%' class='books_table'>\n" . $date_row;
	}
	if ($prev_genre!=$row['genre']|| !$prev_genre) { // группируем жанры в течение одного дня
		$body.=$genre_row;
	}
	$body .= <<<EOD
	<tr>
		<td colspan='2' ><a href='book.php?id={$row['id']}'>{$row['name']}</a> | <a href='author.php?author={$row['auth_id']}'>{$row['author']}</a></td>
	</tr>
	<tr valign="top">
	<td ><img width='50' height='75' src="thumbnail.php?f=book_img/{$row['img']}"></td> 
<!--	<td valign="top"><img width='50' height='75' src="book_img/{$row['img']}"></td> -->

		<td style="font-size:12px" align="justify" valign="top">
		<div class="annotation">{$row['annotation']}</div></td>
	</tr>
EOD;
	$prev_genre = $row['genre'];
	$prev_date = $date;
}

$body .="</table>";

$page_title = $lang['last_added'];
require_once('templates/template.html');

?>
