<?php

require_once('../db.php');
include '../controller/jobreportlist.php';

$jobreportlist = new JobReportList();

respond ($jobreportlist->getAll($_GET['nip']));

?>