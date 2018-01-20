<?php

require_once('../../db.php');
require_once('../../FCM.php');
include '../../controller/push.php';

$push = new Push();

respond ($push->new_msg_notif($_GET['id']));

?>