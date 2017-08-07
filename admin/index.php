<?php header("Content-type:text/html; charset=koi8-r"); 
//	echo md5('entrymaker');
//	echo "<br>";
//	echo md5('holybible');
?>
<html>
	<head>
		<title> Adminka	</title>
	</head>
	<body onload='el = document.getElementById("login"); el.value=""; el.focus();' bgcolor=#DEDEDE>
	<table bgcolor=black width="100%">
	  <tr>
		<td align='center'>
		  <img src='../images/ph_st_logo.jpg' alt=''>
		</td>
	  </tr>
	</table>
	<table height="60%" align="center">
	  <tr>
		<td valign="center">	
	    <form action="check_session.php" method="POST">
		<table>
		  <tr>
			<td>Логин:</td>
			<td>
			  <input id="login" type="text" name="login"><br>
			</td>
		  </tr>
		  <tr>
			<td>Пароль:</td>
			<td>
			  <input type="password" name="pass"><br>
			</td>
		  </tr>
		  <tr>
			<td colspan='2' align='center'>
			  <br>
			  <input type="submit" value="Войти">	
			</td>
		  </tr>
		</table>
	    </form>
		</td>
	  </tr>
	</table>
	</body>
</html>
