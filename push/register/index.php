<?php

require_once('../../db.php');
require_once('../../FCM.php');
include '../../controller/push.php';

$push = new Push();

respond ($push->register($_GET['uname'],$_GET['token'],$_GET['device']));

?>