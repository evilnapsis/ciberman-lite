<?php 
// si el usuario no esta logeado
if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= UserData::getById($_SESSION["user_id"]);
// si el id  del usuario no existe.
if($user==null){ Core::redir("./");}
?>
<div class="container">
<div class="row">
<div class="col-md-12">
<h2>Hola, <?php echo $user->name; ?></h2>
<p>Esta es una funcion de demostracion en la que se puede apreciar el login del usuario.</p>


<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				Rentas
			</div>
			<div class="panel-body">
				<h1>
				<?php echo count(RentData::getAll()); ?>
				</h1>
				<br>
				<a href="./?view=rents&opt=all" class="btn btn-success">Ver Rentas</a>
			</div>
		</div>
	</div>
</div>


</div>
</div>
</div>