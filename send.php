<?php

$where = 'wifi@st.uz';

$match = array( "nazv_zav" => "�������� ���������",
				"kol_table" => "���������� ��������",
				"address" => "�����",
				"phone" => "�������",
				"price" => "���� ������-�����",
				"contact" => "���������� ����",
				"contact_phone" => "������� ���������� ����",
				"email" => "����������� �����",
				"comments" => "����������",
				"from" => "\r\n���������� � �����"
			  );
				

	$theme = "=?koi8-r?B?" . base64_encode('������ � ����� ��������� Wi-Fi') . "?=";	
	$message = "������������! \r\n\r\n���������� ��� ������ ���������:\r\n\r\n";


foreach($_POST as $key => $value) {

  switch ($_POST['from']) {

	case "wifi.uz" 		: $value = iconv("UTF-8", "KOI8-R", $value); break;
	case "wifi.st.uz"	: $value = iconv("CP1251", "KOI8-R", $value); break;
  }

  $message.= $match[$key] .": $value\r\n";
}
	
	$message .= "\r\n--------------------";
	$message .= "\r\n� ���������, �������� Sharq Telekom";
	
	$params  = "From: =?koi8-r?B?" . base64_encode('���� wifi.st.uz') . "?=" . " <wifi@st.uz>\n";
	$params .= "Reply-To: =?koi8-r?B?" . base64_encode('���� wifi.st.uz') . "?=" . " <wifi@st.uz>\n";
	$params .= "Content-type: text/plain; charset=koi8-r\n";
	$params .= "Content-transfer-encoding: 8bit"; // ��� ���� ������

	if ( !mail($where, $theme, $message, $params) )
	  die("������ �� �����������!");

	if ($_POST['from']=='wifi.uz') header("location: http://www.wifi.uz/send.php");
	  else	header("location: http://wifi.st.uz/include/send.php");
?>
