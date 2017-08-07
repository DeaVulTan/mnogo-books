<html>
  <head>
	<title></title>
	<style type='text/css'>
	* {font-family: Times, Verdana; font-size: 15px}
	
	#main_form input, textarea { width : 20em; font-size: 16px; margin-bottom: 8px;}
	#main_form input[type="submit"] { width: 10em; }
	H1 { font-size: 24 px; text-align: center; margin-bottom: 8px;}
	#terms td {text-align: justify;}
	</style>
<!--	<link rel='stylesheet' type='text/css' href='main.css'>   -->
  </head>

  <body>
  
  <table border='0'>
	<tr>
	  <td width="40%">
  
  <form id='main_form' action=send.php method=POST>
  <table border='0' cellpadding=0>
  <colgroup>
	<col width="100px" valign="top" style="margin: 0px; margin-right:10px;">
	<col width="*" valign="top">  
  </colgroup>
	<tr>
	  <td colspan='2'><H1>Регистрация нового участника </H1>
	  </td>
	</tr>  
	<tr>
	  <td><b>Название заведения:</b> </td>
	  <td><input name='nazv_zav'></td>
	</tr>
	<tr>
	  <td>Количество столиков: </td>
	  <td><input name='kol_table'></td>
	</tr>
	<tr>
	  <td>Адрес: </td>
	  <td><textarea rows=5 cols=25 name='address'></textarea></td>
	</tr>
	<tr>
	  <td>Телефон: </td>
	  <td><input name='phone'></td>
	</tr>
	<tr>
	  <td>Цена бизнес-ланча: </td>
	  <td><input name='price'></td>
	</tr>
	<tr>
	  <td>Контактное лицо: </td>
	  <td><input name='contact'></td>
	</tr>
	<tr>
	  <td>Телефон контактного&nbsp;лица: </td>
	  <td><input name='contact_phone'></td>
	</tr>
	<tr>
	  <td><b>Электронная почта:</b> </td>
	  <td><input name='email'></td>
	</tr>
	<tr>
	  <td>Примечание: </td>
	  <td><textarea rows=5 cols=25 name='comments'></textarea></td>
	</tr>
	<tr>
	  <td></td>
	  <td><input type='submit' value='Отправить заявку'></td>
	</tr>
  </table>
  </form>
  </td>
  
  <td valign='top'>
  <table id='terms' border='0' width="90%" align="center">
  <colgroup>
	<col width="20px" valign="top" align="left" style="margin: 0px; margin-right:10px;">
	<col width="*" valign="top">  
  </colgroup>
  <tr>
	<td colspan='2' valign='top'>
	  <H1>Условия для участников проекта</H1>
	</td>	
  </tr>  
  <tr>
	<td>1.</td>
	<td>Партнер принимает на себя
	обязательства размещать рекламные
	материалы Провайдера в зоне действия
	своего предприятия, способствовать
	продвижению на рынке услуг Провайдера,
	рекламировать его торговую марку. С этой
	целью Партнер размещает следующие
	рекламные материалы: буклеты,стикеры на
	входе, плакаты, вывески, витражи, включая
	рекламные слоганы и логотипы в
	предметах, используемых Партнером, как
	ручки, книги меню, пепельницы и др. Так же
	Партнер обязуется размещать на столиках
	заведения рекламные лефлеты Провайдера,
	с которыми посетители могут
	ознакомиться до осуществления заказа.
	</td>
  </tr>
  <tr>
	<td>2.</td>
	<td>Партнер предоставляет материалы о
	заведении для публикации на Яндексе:
	логотип в любом формате (желательно в
	формате cdr), описание (не более 700 знаков с
	учетом пробелов и знаков препинания),
	адрес, телефон, часы работы, адрес сайта
	(если есть).
	</td>
  </tr>
  <tr>
	<td>3.</td>
	<td>Провайдер   предоставляет   Партнеру  
	информацию, необходимую для
	рекламирования своих услуг, в том числе 
	(технические данные продукции, описание
	услуг и другую информацию).
	</td>
  </tr>
  <tr>
	<td>4.</td>
	<td>Партнер обязуется не размещать в своем
	офисе рекламную продукцию третьих лиц,
	оказывающих на рынке аналогичные с
	Провайдером услуги.
	</td>
  </tr>
  </table>
  </td>
  </tr>
  </table>

  </body>
</html>