<?php
if(isset($_SESSION["user_id"])){
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){
	$user = new RentData();
	$user->equipment_id = $_POST["equipment_id"];
	$user->start_date = $_POST["start_date"];
	$user->start_time = $_POST["start_time"];
	$user->finish_time = $_POST["finish_time"];
	$user->person_name = $_POST["person_name"];
	$user->price = $_POST["price"];
	$user->user_id = $_SESSION["user_id"];


	$user->add();
	Core::alert("Renta Agregada!");
	Core::redir("./?view=rents&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="upd"){
if(count($_POST)>0){
	$user = RentData::getById($_POST["user_id"]);


	$user->equipment_id = $_POST["equipment_id"];
	$user->start_date = $_POST["start_date"];
	$user->start_time = $_POST["start_time"];
	$user->finish_time = $_POST["finish_time"];
	$user->person_name = $_POST["person_name"];
	$user->price = $_POST["price"];
	$user->user_id = $_SESSION["user_id"];

	$user->update();


	Core::alert("Renta actualizada!");
	Core::redir("./?view=rents&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$user = RentData::getById($_GET["id"]);
	//if($user->id!=$_SESSION["user_id"]){
		$user->del();
//	}
	Core::alert("Renta eliminada!");
	Core::redir("./?view=rents&opt=all");
}
}


?>