<?php
require_once ('libs/common/global_inc.php');
if (is_loged_in ()) {
	header ( 'Location: ' . WEB_BASE_COMMON . 'index.php' );
	die ();
}


$db_connection = new pg_database_class ();
if (false === $db_connection->open_connection ( FUNCTION_LIBRARY_POSTGRES_DB_NAME, FUNCTION_LIBRARY_POSTGRES_USER, FUNCTION_LIBRARY_POSTGRESS_PASSWORD )) {
	trigger_error ( 'Database DEAD...' . $db_connection->get_last_error_message () );
	exit ();
}


foreach($_REQUEST as $key=>$val) {
	$_REQUEST[$key] =  make_database_safe(strip_tags(trim($val)));
}

if($_REQUEST && isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'verify_user_id') {
	$sql = "Select id from wl_users where user_id='" . $_REQUEST ['inputUserName'] . "'";
	$rs = $db_connection->db_query($sql);
	if( $rs === false || pg_num_rows($rs) > 0)
	{
		echo json_encode(array("status"=>"FAIL","message"=>"User Name Already Taken"));
		die();
	}
	echo json_encode(array("status"=>"SUCCESS"));
	die();
}

if($_REQUEST && isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'verify_email') {
	$sql = "Select id from wl_users where email_address ='" . $_REQUEST ['email'] . "'";
	$rs = $db_connection->db_query($sql);
	if( $rs === false || pg_num_rows($rs) > 0)
	{
		echo json_encode(array("status"=>"FAIL","message"=>"Email Already Taken"));
		die();
	}
	echo json_encode(array("status"=>"SUCCESS"));
	die();
}


if($_REQUEST['inputConPassword'] != $_REQUEST['inputPassword']){
	echo json_encode(array("status"=>"FAIL","message"=>"Password Don't Match"));
	die();

}
$_REQUEST['inputPassword'] = sha1($_REQUEST['inputPassword']);


$sql = "SELECT * from wl_challenage where id='" . $_REQUEST ['wtc'] . "'";
$rs = $db_connection->db_query ( $sql );
if ($rs === false) {
	die ( "bad sql" . pg_last_error ( $db_connection->get_pg_resource () ) );
}
$output = '';
$row = pg_fetch_assoc( $rs );
$wtcId = $row['id'];


$sql = "Insert into wl_users (last_name,first_name,user_id,email_address,password,sec_question,sec_ans) 
		VALUES ('{$_REQUEST['lname']}','{$_REQUEST['fname']}','{$_REQUEST['inputUserName']}','{$_REQUEST['email']}','{$_REQUEST['inputPassword']}','{$_REQUEST['secquestion']}','{$_REQUEST['secans']}')";
$rs = $db_connection->db_query($sql);
if( $rs === false || pg_affected_rows($rs) == 0)
{
	echo json_encode(array("status"=>"FAIL","message"=>$sql + pg_last_error ( $db_connection->get_pg_resource () )));
	die();
}

$sql = "SELECT * from wl_users where user_id='" . $_REQUEST ['inputUserName'] . "'";
$rs = $db_connection->db_query ( $sql );
$row = pg_fetch_assoc( $rs );
$uid = $row['id'];

$sql = "Insert into wlc_user_xref (wlc_id,user_id,intial_weight,intial_investment)
VALUES ('$wtcId','$uid','{$_REQUEST['initWeight']}','{$_REQUEST['dollars']}')";
$rs = $db_connection->db_query($sql);
if( $rs === false || pg_affected_rows($rs) == 0)
{
	echo json_encode(array("status"=>"FAIL","message"=>pg_last_error ( $db_connection->get_pg_resource () )));
	die();
}

$sql = "SELECT * from wlc_user_xref where user_id='$uid' and wlc_id='$wtcId'";
$rs = $db_connection->db_query ( $sql );
$row = pg_fetch_assoc( $rs );
$uid = $row['id'];

$sql = "Insert into wlc_weight_entry (wtc_xref_id,weight)
VALUES ('$uid','{$_REQUEST['initWeight']}')";
$rs = $db_connection->db_query($sql);
if( $rs === false || pg_affected_rows($rs) == 0)
{
	echo json_encode(array("status"=>"FAIL","message"=>pg_last_error ( $db_connection->get_pg_resource () )));
	die();
}
echo json_encode(array("status"=>"SUCCESS"));
?>