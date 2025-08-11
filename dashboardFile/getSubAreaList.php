<?php
session_start();
include '../dashboardFile/branchProcess.php';

$user_id = $_SESSION['userid'];
$branch_id = $_POST['branch_id'];
$branchProcess = new branchProcess();
$area_list = $branchProcess->getAreaList($branch_id,$user_id);

echo json_encode($area_list);