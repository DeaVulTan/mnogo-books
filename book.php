<?php

require_once('functions.php');

if (!connect()) die(1);

if (! (is_numeric($_GET['id'])) ) { 
    require_once('templates/template.html'); 
    die(1);
}

$id = $_GET['id'];

    $res = mysql_query("SELECT *, name_" . SelectedLanguage . " AS name, annotation_" . SelectedLanguage . " as annotation FROM books WHERE id=$id");
    if (!mysql_num_rows($res)) error();

/*
    echo "<pre>";
    print_r($arr); 
    die();
*/

    $book = mysql_fetch_assoc($res);
    $query = mysql_query("SELECT name_" . SelectedLanguage . " AS name FROM authors WHERE id=" . $book['auth_id']);
    if (!mysql_num_rows($query)) error();
    
	list($page_title) = mysql_fetch_array($query); // author name
	$size[0] = $book; // функция get_size принимает массив книг из табл. books
	$size = get_size($size);

	$book['type'] = $book['type']!=""?", {$lang['format']}: {$book['type']}":"";

	$genre = trim(get_genre_by_id($book['genre']));

$body = print_title();
$body .= <<<EOD
<a href='http://book.stream.uz'><span class='genre_link' style="color:green;">{$lang['library']}</span></a>
/<a href='http://book.stream.uz/author_list.php?switch=1&genre={$book['genre']}'><span class='genre_link'>$genre</span></a>
<center><h1 class="contentArticleLink" style="font-size:20px">{$book['name']}</h1><font size="-1">{$lang['rating']}: {$book['load_total']}{$book['type']}</font></center>
<table align='left' width="100%" style="margin-right:2px" border='0'>
    <tr>
	  <td align='center'><h3>{$lang['abstract']}</h3></td>
    </tr>
	<tr>
	  <td valign='top' align='justify'>
		<img class='contentImageLeft' width='200' height='325' src='book_img/{$book['img']}'>
		<div class="annotation">{$book['annotation']}</div>
	  </td>
    </tr>
    <tr>
	  <td valign='bottom' align='right' height="10%">
	    <span class="contentFooterLinks" align='center'>
	    <a href='download.php?id={$book['id']}'>
<img width="20" hspace='3' border="0" height="26" align="absmiddle" 
src='images/foliant.png'>{$lang['download']} ($size[$id])</a>&nbsp;|&nbsp;<a href='author.php?author={$book['auth_id']}'>{$lang['to_authors_page']}</a>
	    </span>
	  </td>
    </tr>
</table>
EOD;

require_once('templates/template.html'); 

?>
