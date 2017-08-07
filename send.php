<?php

$where = 'wifi@st.uz';

$match = array( "nazv_zav" => "Название заведения",
				"kol_table" => "Количество столиков",
				"address" => "Адрес",
				"phone" => "Телефон",
				"price" => "Цена бизнес-ланча",
				"contact" => "Контактное лицо",
				"contact_phone" => "Телефон контакного лица",
				"email" => "Электронная почта",
				"comments" => "Примечание",
				"from" => "\r\nОтправлено с сайта"
			  );
				

	$theme = "=?koi8-r?B?" . base64_encode('Заявка о новом участнике Wi-Fi') . "?=";	
	$message = "Здравствуйте! \r\n\r\nПринимайте еще одного участника:\r\n\r\n";


foreach($_POST as $key => $value) {

  switch ($_POST['from']) {

	case "wifi.uz" 		: $value = iconv("UTF-8", "KOI8-R", $value); break;
	case "wifi.st.uz"	: $value = iconv("CP1251", "KOI8-R", $value); break;
  }

  $message.= $match[$key] .": $value\r\n";
}
	
	$message .= "\r\n--------------------";
	$message .= "\r\nС уважением, компания Sharq Telekom";
	
	$params  = "From: =?koi8-r?B?" . base64_encode('Сайт wifi.st.uz') . "?=" . " <wifi@st.uz>\n";
	$params .= "Reply-To: =?koi8-r?B?" . base64_encode('Сайт wifi.st.uz') . "?=" . " <wifi@st.uz>\n";
	$params .= "Content-type: text/plain; charset=koi8-r\n";
	$params .= "Content-transfer-encoding: 8bit"; // для темы письма

	if ( !mail($where, $theme, $message, $params) )
	  die("Письмо не отправилось!");

	if ($_POST['from']=='wifi.uz') header("location: http://www.wifi.uz/send.php");
	  else	header("location: http://wifi.st.uz/include/send.php");
?>
