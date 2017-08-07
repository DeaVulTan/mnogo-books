<?php
require_once("../const.php");

$url = "http://files.stream.uz/Books";
$path = "/srv/Books";

if (isset($_POST['relocate'])) {
    $oldpath = trim($_POST['oldpath']);
    $newpath = trim($_POST['newpath']);
    
    $error = "";
    
    if (empty($error) && empty($oldpath)) {
        $error = "Old path cannot be empty";
    }
    if (empty($error) && empty($newpath)) {
        $error = "New path cannot be empty";
    }
    
    if (empty($error) && substr($oldpath, 0, 1) != '/') {
        $oldpath = '/'.$oldpath;
    }
    if (empty($error) && substr($newpath, 0, 1) != '/') {
        $newpath = '/'.$newpath;
    }
    
    if (empty($error) && !file_exists($path.$newpath)) {
        $error = "Target file does not exist!";
    }
    
    if (empty($error)) {
        $SiteDB = mysql_connect($host, $login, $pass) or die("SQL Connect Error");
        mysql_select_db($db_name, $SiteDB) or die("Database Select Error");

        $error = "Processing:<br />";
        $oldurl = $url.$oldpath;
        $error .= "<a href=\"" . $oldurl . "\">" . $oldurl . "</a> => ";
        
        $Select['Query'] = "
            SELECT * 
            FROM `books`
            WHERE 
                `uri` = '" . $url.$oldpath . "'";
        $Select['Result'] = mysql_query($Select['Query'], $SiteDB) or die("Error Select: ".mysql_error());
        if (mysql_num_rows($Select['Result']) == 0) {
            $error .= "<br />Provied old path was not found in the database.";
        }
        elseif (mysql_num_rows($Select['Result']) > 1) {
            $error .= "<br />Something went wrong. Contact your database administrator.";
        } else {
            $Select['Row'] = mysql_fetch_array($Select['Result']);
            $book_id = $Select['Row']['id'];
        }
        unset ($Select);
    
        if ($book_id) {
            $Update['Query'] = "
                UPDATE `books` 
                SET
                    `uri` = '" . $url.$newpath . "'
                WHERE 
                    `id` = '" . $book_id . "'";
            $Update['Result'] = mysql_query($Update['Query'], $SiteDB) or die("Error Update: ".mysql_error());
            $newurl = $url.$newpath;
            $error .= "<a href=\"" . $newurl . "\">" . $newurl . "</a><br />";
            $error .= "All done!<br />";
            unset($Update);
        }

        unset($_POST['oldpath']);
        unset($_POST['newpath']);
        
        mysql_close($SiteDB);
    }
}
if ($error) {
    echo $error."<br /><br />";
}
?>
<form name="relocate" action="" method="post">
    Old path: <input name="oldpath" type="text" size="72" maxlength="512"<?php if (isset($_POST['oldpath'])) echo " value=\"" . stripslashes($_POST['oldpath']) . "\""; ?>><br />
    New path: <input name="newpath" type="text" size="72" maxlength="512"<?php if (isset($_POST['newpath'])) echo " value=\"" . stripslashes($_POST['newpath']) . "\""; ?>><br />
    <input name="relocate" type="submit" value="Relocate!" />
</form>