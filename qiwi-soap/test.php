<?php
/**
 * ������ ������ ������� �� ������� QIWI �������� (https://ishop.qiwi.ru/services/ishop?wsdl)
 * ����������� ������ ������ IShopServerWSService ��������� ��� ������, ������� ������� � ������������.
 * ��� ������ ���������� ������������ "������-������" (����� �������� ������ � ����� IShopServerWSService.php)
 * � ������� ����� ������ IShopServerWSService ������� � ���� ���� ������ � ���� ���������.
 * 
 **/
 
/*
 * ��������� ��������
 */ 
define('LOGIN', 229549);
define('PASSWORD', 'batsillachmod700');
 
include("IShopServerWSService.php");



$options = array (
	'location' => 'http://ishop.qiwi.ru/services/ishop',
	'trace'    => 1
);
$service = new IShopServerWSService('IShopServerWS.wsdl', $options);

/**
 * @param $txn_id - ����� ����������� �����
 *
 */
function cancelBill($txn_id) {
	global $service;
	
	// ��������� ������-������
	$params = new cancelBill();
	$params->login = LOGIN;
	$params->password = PASSWORD;
	$params->txn = $txn_id;
	
	// �������� ����� ������� � �����������
	$res = $service->cancelBill($params);

	// ������� ���������
	print($res->cancelBillResult);

	// ��� ������� (����� ���� �������)
	// print($service->__getLastRequest());
}


/**
 * @param $phone (string) - ����� �������� (QIWI ��������), �� ������� ����� ������������ ����
 * @param $amount (string) - ����� � ������ (� ������� "�����"."�������")
 * @param $txn_id (string) - ����� ����� (���������� � �������� ��������)
 * @param $comment (string) - �����������
 * @param $lifetime (string) - ���� �������� ����� (� ������� dd.mm.yyyy HH:MM:SS)
 * @param $alarm (int) - �����������
 * @param $create (bool) - ���������� ��������������������� ������������
 *
 **/
function createBill($phone, $amount, $txn_id, $comment, $lifetime='', $alarm=1, $create=true)
{
	global $service;
	
	$params = new createBill();
	$params->login = LOGIN; // �����
	$params->password = PASSWORD; // ������
	$params->user = $phone; // ������������, �������� ������������ ����
	$params->amount = ''.$amount; // �����
	$params->comment = $comment; // �����������
	$params->txn = $txn_id; // ����� ������
	$params->lifetime = $lifetime; // ����� ����� (���� �����, ������������ �� ��������� 30 ����)
	
	// ���������� ������������ � ������������ ����� (0 - ���, 1 - ������� ���, 2 - ������� ������)
	// ����������� ������� ��� ��������, �������� ������ ���������, ������������������ �� ����� "������� ������"
	$params->alarm = $alarm; 

	// ���������� ���� ��������������������� ������������
	// false - ���������� ������ � ������, ���� ������������ �� ���������������
	// true - ���������� ���� ������
	$params->create = $create;

	$res = $service->createBill($params);
echo '<pre>'.print_r($res, true).'</pre>';
	$rc = $res->createBillResult;
	return $rc;
}

// ������ ������
$rc = createBill('8888888888', '0.01', 'TEST-1', 'Test bill');

// ��������� ��� $rc, ������ ������/������������ ������������ � ����������� �� ����
// ����� ��� �������:
print($rc);

?>
