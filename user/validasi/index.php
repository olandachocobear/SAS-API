<?php

require_once('../../db.php');
include '../../controller/validasi.php';

$validasi = new Validasi();

respond ($validasi->validateSPK($_GET['nip'],$_GET['spk']));

?>