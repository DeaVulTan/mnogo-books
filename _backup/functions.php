<?php
/***************************************************************************
 *   Copyright (C) 2006 by ash                                             *
 *   alexey_s@mail.st.uz                                                   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/



error_reporting(0);


// LANG - Start

function CurrentURL() {
    $currentURL = $_SERVER['HTTP_REFERER'];

    $currentURL = explode("?", $currentURL);
    $currentURL = $currentURL[0];

    return $currentURL;
}

function mb_ucfirst($string) {
    $string = mb_ereg_replace("^[\ ]+","", $string);
    $string = mb_strtoupper(mb_substr($string, 0, 1, "KOI8-R"), "KOI8-R").mb_substr($string, 1, mb_strlen($string), "KOI8-R" );
    return $string;
}

function json_safe_decode($var)
{
   $var = json_decode($var, true);
   return json_fix_cyr($var);
}

function json_fix_cyr($var)
{
   if (is_array($var)) {
       $new = array();
       foreach ($var as $k => $v) {
           $new[json_fix_cyr($k)] = json_fix_cyr($v);
       }
       $var = $new;
   } elseif (is_object($var)) {
       $vars = get_object_vars($var);
       foreach ($vars as $m => $v) {
           $var->$m = json_fix_cyr($v);
       }
   } elseif (is_string($var)) {
       $var = iconv('utf-8', 'koi8-r', $var);
   }
   return $var;
}

$SiteLanguages = array(
    'uz' => array(
        'ru' => 'Узбекский',
        'uz' => 'O`zbekcha',
    ),
    'ru' => array(
        'ru' => 'Русский',
        'uz' => 'Ruscha',
    ),
);

if (isset($_GET['lang'])) {
    if (strlen($_GET['lang']) == 2) {
        $SetLang = mb_convert_case($_GET['lang'], MB_CASE_LOWER);
        if (in_array($SetLang, array_keys($SiteLanguages))) {
            setcookie("LANG", $SetLang, time()+259200, "/");
        }
    }    
    header("Location: ".CurrentURL());
}

switch ($_COOKIE['LANG']) {
    case "ru":
    case "uz":
        break;

    default:
        setcookie("LANG", "ru", time()+259200, "/");
        $_COOKIE['LANG'] = "ru";
        break;
}

define ("SelectedLanguage", $_COOKIE['LANG']);

define("SitePath", "/var/www/book.stream.uz");
define("LangDir", "/htdocs/lang");
define("LangPath", SitePath.LangDir);

if (file_exists(LangPath."/language-".SelectedLanguage.".json")) {
    $lang = json_safe_decode(file_get_contents(LangPath."/language-".SelectedLanguage.".json"));
}
else {
    $lang = json_safe_decode(file_get_contents(LangPath."/language-ru.json"));
}
// LANG - End 


$no_author = '<div>' . $lang['nuauthor'] . '</div>';

$no_books = '<div>' . $lang['nobooks'] . '</div>';

function error() {
    global $no_author;
    require_once('templates/template.html');
    die();
}


function connect() {	// connect
	require_once("const.php");
	
	if ( !mysql_connect($host, $login, $pass) ) {
		echo "Error: connect";
		return 0;
	}
	if ( !(mysql_select_db($db_name)) ) {
		echo "Error: select_db";
		return 0;
	}
	
	if ( !mysql_query("SET NAMES koi8r")) {
		echo "Error: SELECT";
		return 0;
	}

	return 1;
}

function alphabet() {
	$latin = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$cyrillic = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЭЮЯ';
	$sql = "SELECT UPPER(LEFT(name,1)) as name FROM authors GROUP BY LEFT(name,1)";
	$query = mysql_query($sql) or die("Error: SELECT");
	while ($row = mysql_fetch_assoc($query)) 
	  $whatinbase .= $row["name"];
		
	for($i=0; $i<strlen($latin); $i++)
	  if (strpos($whatinbase, $latin[$i])!==false)  // автор есть в базе
		echo "<a href='author_list.php?bukva={$latin[$i]}'>{$latin[$i]}</a>&nbsp;";
	  else
		echo "$latin[$i]&nbsp;";
	
	echo "<br>";
	for($i=0; $i<strlen($cyrillic); $i++)
	  if (strpos($whatinbase, $cyrillic[$i])!==false)  // автор есть в базе
		echo "<a href='author_list.php?bukva={$cyrillic[$i]}'>{$cyrillic[$i]}</a>&nbsp;";
	  else
		echo "$cyrillic[$i]&nbsp;";
		
	echo "<br>";
}

function show_genres($genres) {
$head = <<<EOD
<!-- #main sections menu# -->
<table id="idLeftToolbarBodyLayoutTable" width="100%" border="0" cellpadding="0" cellspacing="5" class="designLayoutTable">
EOD;
echo $head;
  echo $about = <<<EOD
  <!-- #menu item#  -->
  <tr>
	<!-- #item icon# -->
	<td width="15" height="15">
	  <img src="images/icn_ssection.gif" width="15" height="11" alt="">
	</td>
	<!-- #item label# -->
	<td width="100%" height="15">
	  <a href='about.php' class="designTBXMMenuText">О проекте</a>
	</td>
  </tr>
EOD;
    while ($row = mysql_fetch_assoc($genres)) {
	$tr = <<<EOD
  <!-- #menu item#  -->
  <tr>
	<!-- #item icon# -->
	<td width="15" height="15">
	  <img src="images/icn_ssection.gif" width="15" height="11" alt="">
	</td>
	<!-- #item label# -->
	<td width="100%" height="15">
	  <a href='author_list.php?genre={$row['id']}' class="designTBXMMenuText">
	  {$row['name']}</a>
	  <span class="designCiphers">({$row['total_books']})</span>
	</td>
  </tr>
EOD;
	  echo $tr;
	}
  echo "</table>\n";
}

function get_genre_by_id($id) {
  $query = mysql_query("SELECT name FROM genre WHERE id=$id");
  
  if ($query) {
	$name = mysql_fetch_row($query); 
	return $name[0];
  }
  else return false;
}


function human_size($bytes, $decimal = '2' ) {
   global $lang;
   if( is_numeric( $bytes ) ) {
     $position = 0;
     $units = array( " ".$lang['units']['byte']['long'], " ".$lang['units']['kilobyte']['short'], " ".$lang['units']['megabyte']['short'], " ".$lang['units']['gigabyte']['short'], " ".$lang['units']['terabyte']['short'] );
     while( ($bytes >= 1024) && ( (float) $bytes/1024 >= 1) ) {
         $bytes /= (float) 1024;
         $position++;
     }
     return round($bytes, $decimal) . $units[$position];
   }
   else {
     return "0 Байт";
   }
}

function get_size($arr) {
global $lang;
/*// установка соединения
$ftp_server = 'ftp.stream.uz';
$ftp_user_name = 'anonymous';
$ftp_user_pass = 'anonym@stream.uz';
$conn_id = ftp_connect($ftp_server);

// проверка имени пользователя и пароля
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// получение размера файлов 
$size = array();
foreach ($arr as $book) {
// make uri into a local path for ftp server
  $book['uri'] = substr_replace($book['uri'], '', 0, strlen("ftp://$ftp_server"));
  $res = ftp_size($conn_id, $book['uri']);
	$size[$book['id']] = ($res!= -1) ? human_size($res):"<span style='color: red; font-size:12px'>Не удалось определить размер файла</span>";
}

// закрытие соединения
ftp_close($conn_id);

return $size;*/
$size = array();
foreach ($arr as $book) {
	$url = trim($book['uri']);
	$sch = parse_url($url);$sch = $sch['scheme'];
	if (($sch == "http") || ($sch == "https")) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$head = curl_exec($ch);
		curl_close($ch);
		preg_match('/Content-Length: ([0-9]+)/',$head,$matches);
		$size[$book['id']] = ($matches[1] != '') ? human_size($matches[1]):"<span style='color: red; font-size:12px'>" . $lang['size_unknown'] . "</span>";
	} else $size[$book['id']] = "<span style='color: red; font-size:12px'>" . $lang['size_unknown'] . "</span>";
}
return $size;
}
function print_title($title="") {
$ret = <<<EOD
		<center><table id="Table" width="30%" height="57" border="0" cellpadding="0" cellspacing="0">
                 <tr>
                   <td width="30" height="57"><img src="images/pc_ptitle_left.gif" width="30" height="57" alt=""></td>
                   <td width="100%" height="57" class="designPCTitleCont" align="center" valign="middle" nowrap>
                 <!-- #page title# -->
                     <h1 class="designPCTitleText">{$title}</h1>
                   </td>
                   <td width="30" height="57"><img src="images/pc_ptitle_right.gif" width="30" height="57" alt=""></td>
                 </tr>
                 </table></center>
EOD;
return $ret;
}
?>
