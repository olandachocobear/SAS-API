<?php

require_once('../../db.php');
include '../../controller/schedule.php';

$schedule = new Schedule();

respond ($schedule->confirm_interview($_GET['id'],$_GET['answer']));

?>