<?php

require_once('../../db.php');
require_once('../../FCM.php');
include '../../controller/push.php';

$push = new Push();

if (isset($_GET['icon']))
	if (isset($_GET['sound']))
		respond ($push->test_notif_w_actions($_GET['id'],$_GET['icon'],$_GET['sound']));
	else
		respond ($push->test_notif_w_actions($_GET['id'],$_GET['icon']));
else if(isset($_GET['sound']))
	respond ($push->test_notif_w_actions($_GET['id'],null,$_GET['sound']));
else
	respond ($push->test_notif_w_actions($_GET['id']));
	

?>