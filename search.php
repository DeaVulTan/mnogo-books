<?php
  require_once('functions.php');

  if (!connect()) error();

  if ($_GET['where']) {
	$table="books";
	$page_title = $lang['books'];
	$link = "book.php?id";
  }
  else {
    $table="authors";
	$page_title = $lang['authors'];
	$link = "author.php?author";
  }
  
  $search = $_GET['text'];
  if (!($query=mysql_query("SELECT *, name_" . SelectedLanguage . " AS name FROM $table WHERE name_" . SelectedLanguage . " REGEXP '{$search}' ORDER BY name")) ) 
  	error();
	$body = print_title($page_title);
  if (!mysql_num_rows($query)) $body .= "<center>" . $lang['notfound'] . "</center>";
  else {
	$body .= <<<EOD
	<table>
	  <tr>
		<td>
		  <ul>
EOD;
	while($entry=mysql_fetch_assoc($query))	{
	  $body .= "<li><a href='{$link}={$entry['id']}'>{$entry['name']}</a>";
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
