<?php

require_once('../../db.php');
include '../../controller/joblist.php';

$joblist = new JobList();

respond ($joblist->startJob($_GET['kd_job'],$_GET['nip']));

?>