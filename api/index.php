<?php
session_start();
require('../core/core.php');
$api = new appController();
$user = new userController();
$supplier = new supplierController();
$aggregates = new aggregatesController();
$clients = new clientsController();
$employees = new employeesController();
$truck = new truckController();
$trip = new tripController();
$bill = new billController();
$core = new mmlapi();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
@$method = $request->method;
@$model = $request->model;
if($method=="login"){
	$data =  $api->login($request);
	if (session_status() == PHP_SESSION_NONE) {
		session_start();

$truck = new truckController();		}
	//echo json_encode($data);
	if(!array_key_exists('status', $data)){
		$_SESSION['id'] = $data[0]['data'][0]['id'];
		$_SESSION['login'] = true;
	}
	echo json_encode($data);
}elseif($method=="insert"){
	if($model=="user"){
		echo $user->create($request);
	}
	if($model=="supplier"){
		echo $supplier->create($request);
	}
	if($model=="aggregates"){
		echo $aggregates->create($request);
	}
	if($model=="clients"){
		echo $clients->create($request);
	}
	if($model=="employee"){
			echo $employees->create($request);
		}
	if($model=="truck"){
			echo $truck->create($request);
		}
	if($model=="trip"){
			echo $trip->create($request);
		}	
	if($model=="billclient"){
			echo $bill->create($request);
		}	
}elseif($method=="check"){
		@$model = $request->id;
		
}elseif($method=="logout"){
		session_destroy();
    	$_SESSION['id'] = null;
		$_SESSION['login'] = false;
    	return 'signed-out';
}elseif($method=='get'){ 
        if($model=="user"){
			echo $user->retrieve($request);
		} 
		if($model=="supplier"){
			echo $supplier->retrieve($request);
		}
		if($model=="aggregates"){
			//echo 'asdsad';
			echo $aggregates->retrieve($request);
		}
		if($model=="clients"){
			//echo 'asdsad';
			echo $clients->retrieve($request);
		}
		if($model=="employee"){
			echo $employees->retrieve($request);
		}
		if($model=="truck"){
			echo $truck->retrieve($request);
		}
		if($model=="trip"){
			echo $trip->retrieve($request);
		}		
		if($model=="billclient"){
			echo $bill->retrieve($request);
		}	
}elseif($method=='delete'){ 
        if($model=="user"){
			echo $user->delete($request);
		} 
		if($model=="supplier"){
			echo $supplier->delete($request);
		}
		if($model=="aggregates"){
			echo $aggregates->delete($request);
		}
		if($model=="clients"){
			echo $clients->delete($request);
		}
		if($model=="employee"){
			//echo 'asdsad';
			echo $employees->delete($request);
		}
		if($model=="truck"){
			echo $truck->delete($request);
		}	
		if($model=="trip"){
			echo $trip->delete($request);
		}	
		if($model=="billclient"){
			echo $bill->delete($request);
		}			
}elseif($method=='update'){ 
        if($model=="user"){
			echo $user->update($request);
		} 
		if($model=="supplier"){
			echo $supplier->update($request);
		}	
		if($model=="aggregates"){
			echo $aggregates->update($request);
		}	
		if($model=="clients"){
			echo $clients->update($request);
		}
		if($model=="employee"){
			echo $employees->update($request);
		}
		if($model=="truck"){
			echo $truck->update($request);
		}
		if($model=="trip"){
			echo $trip->update($request);
		}	
		if($model=="billclient"){
			echo $bill->update($request);
		}			
}else{

}




?>