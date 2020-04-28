<?php
if(isset($_SESSION["user_id"])){
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){
	$user = new EquipmentData();
	$user->name = $_POST["name"];
	$user->code = $_POST["code"];
	$user->price_hour = $_POST["price_hour"];
	$user->price_half = $_POST["price_half"];
	$user->add();
	Core::alert("Equipo Agregado!");
	Core::redir("./?view=equipments&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="upd"){
if(count($_POST)>0){
	$user = EquipmentData::getById($_POST["user_id"]);
	$user->name = $_POST["name"];
	$user->code = $_POST["code"];
	$user->price_hour = $_POST["price_hour"];
	$user->price_half = $_POST["price_half"];
	$user->update();

	if($_POST["password"]!=""){
		$user->password = sha1(md5($_POST["password"]));
		$user->update_passwd();
		Core::alert("Se ha actualizado el password!");
	}
	Core::alert("Usuario actualizado!");
	Core::redir("./?view=equipments&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$user = EquipmentData::getById($_GET["id"]);
	//if($user->id!=$_SESSION["user_id"]){
		$user->del();
//	}
	Core::alert("Equipo eliminado!");
	Core::redir("./?view=equipments&opt=all");
}
}


?>