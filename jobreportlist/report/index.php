<?php

require_once('../../db.php');
include '../../controller/jobreportlist.php';

$jobreportlist = new JobReportList();

respond ($jobreportlist->postReport($_GET['kd_job'],$_GET['nip'],$_GET['msg']));

?>