<?php

if (!defined("WHMCS")) die("This file cannot be accessed directly");

function gateway_fees_config()
{
	$configarray = array(
		"name" => "Комиссия для платежных шлюзов",
		"description" => "Добавляет в счет коммисию в зависимости от платежного шлюза.",
		"version" => "1.0.1",
		"author" => "service-voice"
	);

	$result = mysql_query('select * from tblpaymentgateways group by gateway');

	while ($data = mysql_fetch_array($result)) {
		$result2 = mysql_query('SELECT * FROM `tblcurrencies`');
		while ($data2 = mysql_fetch_array($result2)) {
			$configarray['fields']["fee_1_" . $data['gateway'] . "_" . $data2['code']] = array(
				"FriendlyName" => $data['gateway'] . ' ' . $data2['code'],
				"Type" => "text",
				"Default" => "0.00",
				"Description" => "сумма"
			);
			$configarray['fields']["fee_2_" . $data['gateway'] . "_" . $data2['code']] = array(
				"FriendlyName" => $data['gateway'] . ' ' . $data2['code'],
				"Type" => "text",
				"Default" => "0.00",
				"Description" => "процент"
			);
		}
	}

	return $configarray;
}

function gateway_fees_activate()
{
	$result = mysql_query('select * from tblpaymentgateways group by gateway');
	while ($data = mysql_fetch_array($result)) {
		$result2 = mysql_query('SELECT * FROM `tblcurrencies`');
		while ($data2 = mysql_fetch_array($result2)) {
			$query2 = "insert into `tbladdonmodules` (module,setting,value) value ('gateway_fees','fee_1_" . $data['gateway'] . "_" . $data2['code'] . "','0.00' )";
			$result2 = mysql_query($query2);
			$query3 = "insert into `tbladdonmodules` (module,setting,value) value ('gateway_fees','fee_2_" . $data['gateway'] . "_" . $data2['code'] . "','0.00' )";
			$result3 = mysql_query($query3);
		}
	}
}

?>
