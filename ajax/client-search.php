<?php
include '../core/core.php';

$app = new mmlapi();
if($_POST)
{


    $data = array('model'=>'clients','order'=>' order by company');
    $conditions = "WHERE company LIKE '%".$_GET['name']."%' OR contact_person LIKE '%".$_GET['name']."%' ";
    $data = array('model'=>'clients', 'condition'=>$conditions,'order'=>' order by company');
    $responsed = $app->getRecord2($data);
    $clients = $responsed['data'];
    echo json_encode($clients);

}
?>