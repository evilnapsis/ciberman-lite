<?php 

//if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= EquipmentData::getById($_SESSION["user_id"]);
// si el id  del usuario no existe.
//if($user==null){ Core::redir("./");}

if(isset($_GET["opt"]) && $_GET["opt"]=="all"):?>
<section class="container">
<div class="row">
	<div class="col-md-12">
		<h1>Lista de Equipos</h1>
	<a href="index.php?view=equipments&opt=new" class="btn btn-default"><i class='glyphicon glyphicon-asterisk'></i> Nuevo</a>
<br><br>
		<?php
		$users = EquipmentData::getAll();
		if(count($users)>0){
			?>
			<div class="box box-primary">
			<table class="table table-bordered datatable table-hover">
			<thead>
			<th>Codigo</th>
			<th>Equipo</th>
      <th>Precio Hora</th>
      <th>Precio Media</th>
			<th></th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td><?php echo $user->code; ?></td>
				<td><?php echo $user->name; ?></td>
        <td>$ <?php echo $user->price_hour; ?></td>
        <td>$ <?php echo $user->price_half; ?></td>
				<td style="width:120px;">
				<a href="index.php?view=equipments&opt=edit&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a>
				<a href="index.php?action=equipments&opt=del&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a>
				</td>
				</tr>
				<?php

			}
 echo "</table></div>";

		}else{
			?>
			<p class="alert alert-warning">No hay Equipos.</p>
			<?php
		}

		?>

	</div>
</div>
</section>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):?>
<section class="container">
<div class="row">
	<div class="col-md-12">
	<h1>Agregar Equipo</h1>
	<br>
<form class="form-horizontal" method="post" id="addproduct" action="index.php?action=equipments&opt=add" role="form">
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Codigo*</label>
    <div class="col-md-6">
      <input type="text" name="code" class="form-control" id="code" placeholder="Codigo">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre del equipo*</label>
    <div class="col-md-6">
      <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre del equipo">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Precio x hora*</label>
    <div class="col-md-6">
      <input type="text" name="price_hour" class="form-control" required id="price_hour" placeholder="Precio x hora">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Precio por 1/2 hora*</label>
    <div class="col-md-6">
      <input type="text" name="price_half" class="form-control" id="price_half" placeholder="Precio por 1/2 hora">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Agregar Equipo</button>
    </div>
  </div>
</form>
	</div>
</div>
</section>

<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="edit"):?>
<div class="container">
<?php $user = EquipmentData::getById($_GET["id"]);?>
<div class="row">
	<div class="col-md-12">
	<h1>Editar Usuario</h1>
	<br>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?action=equipments&opt=upd" role="form">


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Codigo*</label>
    <div class="col-md-6">
      <input type="text" name="code" value="<?php echo $user->code;?>" class="form-control" id="code" placeholder="Codigo">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre del equipo*</label>
    <div class="col-md-6">
      <input type="text" name="name" value="<?php echo $user->name;?>" class="form-control" id="name" placeholder="Nombre del equipo">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Precio x hora*</label>
    <div class="col-md-6">
      <input type="text" name="price_hour" value="<?php echo $user->price_hour;?>" class="form-control" required id="price_hour" placeholder="Precio x hora">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Precio por 1/2 hora*</label>
    <div class="col-md-6">
      <input type="text" name="price_half" value="<?php echo $user->price_half;?>" class="form-control" id="price_half" placeholder="Precio por 1/2 hora">
    </div>
  </div>


  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
      <button type="submit" class="btn btn-primary">Actualizar Equipo</button>
    </div>
  </div>
</form>
	</div>
</div>
</div>
<?php endif; ?>