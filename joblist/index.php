<?php

require_once('../db.php');
include '../controller/joblist.php';

$joblist = new JobList();

respond ($joblist->getAll($_GET['nip']));

?>