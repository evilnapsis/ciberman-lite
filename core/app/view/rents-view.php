<?php 
date_default_timezone_set("America/Mexico_City");
//if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= RentData::getById($_SESSION["user_id"]);
// si el id  del usuario no existe.
//if($user==null){ Core::redir("./");}

if(isset($_GET["opt"]) && $_GET["opt"]=="all"):?>
<section class="container">
<div class="row">
	<div class="col-md-12">
		<h1>Lista de Rentas</h1>
	<div class="row">
    <div class="col-md-6">
  <a href="index.php?view=rents&opt=new" class="btn btn-default"><i class='glyphicon glyphicon-asterisk'></i> Nuevo</a>
</div>
<div class="col-md-6">
<form class="form-inline" >
  <input type="hidden" name="view" value="rents">
  <input type="hidden" name="opt" value="all">
  <div class="form-group">
    <input type="date" name="date_at" value="<?php echo date('Y-m-d');?>" required class="form-control" id="exampleInputName2" placeholder="Jane Doe">
  </div>
  <button type="submit" class="btn btn-info">Filtrar</button>
</form>

  </div>
</div>

  <br><br>
		<?php
    $users = array();
    if(isset($_GET["date_at"])){
    $users = RentData::getAllBy("start_date", $_GET["date_at"]);

    }else{
		$users = RentData::getAll();
    }
		if(count($users)>0){
			?>
			<div class="box box-primary">
			<table class="table table-bordered datatable table-hover">
			<thead>
			<th>Id</th>
      <th>Cliente</th>
      <th>Equipo</th>
      <th>Fecha</th>
			<th>Inicio</th>
      <th>Fin</th>
      <th>Total $</th>
			<th></th>
			</thead>
			<?php
      $total=0;
			foreach($users as $user){
        $eq= EquipmentData::getById($user->equipment_id);
				?>
				<tr>
				<td><?php echo $user->id; ?></td>
        <td><?php echo $user->person_name; ?></td>
				<td><?php echo $eq->name; ?></td>
        <td><?php echo $user->start_date; ?></td>
        <td><?php echo $user->start_time; ?></td>
        <td><?php echo $user->finish_time; ?></td>
        <td>$ <?php echo $user->price; ?></td>
				<td style="width:120px;">
				<a href="index.php?view=rents&opt=edit&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a>
				<a href="index.php?action=rents&opt=del&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a>
				</td>
				</tr>
				<?php
$total+=$user->price;
			}
 echo "</table></div>";
echo "<h1>Total: $ $total</h1>";
		}else{
			?>
			<p class="alert alert-warning">No hay Rentas.</p>
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
	<h1>Agregar Renta</h1>
	<br>
<form class="form-horizontal" method="post" id="addproduct" action="index.php?action=rents&opt=add" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Equipo*</label>
    <div class="col-md-6">
      <select class="form-control" name="equipment_id" required>
        <option value="">-- SELECCIONE --</option>
        <?php foreach(EquipmentData::getAll() as $e):?>
        <option value="<?php echo $e->id; ?>"><?php echo $e->name; ?></option>
        <?php endforeach; ?>
      </select>

    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Cliente*</label>
    <div class="col-md-6">
      <input type="text" name="person_name" class="form-control" id="person_name" placeholder="Cliente">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Fecha*</label>
    <div class="col-md-6">
      <input type="date" value="<?php echo date('Y-m-d');?>" name="start_date" class="form-control" id="start_date" placeholder="Fecha">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Hora inicio *</label>
    <div class="col-md-6">
      <input type="time" value="<?php echo date('H:i:s');?>" name="start_time" required class="form-control" id="start_time" placeholder="Hora inicio ">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Hora fin*</label>
    <div class="col-md-6">
      <input type="time" name="finish_time" class="form-control"  id="finish_time" placeholder="Hora fin">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Total $*</label>
    <div class="col-md-6">
      <input type="text" name="price" class="form-control" id="price" placeholder="Total $">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Agregar Renta</button>
    </div>
  </div>
</form>
	</div>
</div>
</section>

<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="edit"):?>
<div class="container">
<?php $user = RentData::getById($_GET["id"]);?>
<div class="row">
	<div class="col-md-12">
	<h1>Editar Renta</h1>
	<br>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?action=rents&opt=upd" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Equipo*</label>
    <div class="col-md-6">
      <select class="form-control" name="equipment_id" required>
        <option value="">-- SELECCIONE --</option>
        <?php foreach(EquipmentData::getAll() as $e):?>
        <option value="<?php echo $e->id; ?>" <?php if($user->equipment_id==$e->id){ echo "selected"; }?>><?php echo $e->name; ?></option>
        <?php endforeach; ?>
      </select>

    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Cliente*</label>
    <div class="col-md-6">
      <input type="text" name="person_name" value="<?php echo $user->person_name; ?>" class="form-control" id="person_name" placeholder="Cliente">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Fecha*</label>
    <div class="col-md-6">
      <input type="date" value="<?php echo $user->start_date; ?>" name="start_date" class="form-control" id="start_date" placeholder="Fecha">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Hora inicio *</label>
    <div class="col-md-6">
      <input type="time" value="<?php echo $user->start_time; ?>" name="start_time" required class="form-control" id="start_time" placeholder="Hora inicio ">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Hora fin*</label>
    <div class="col-md-6">
      <input type="time" name="finish_time" value="<?php echo $user->finish_time; ?>" class="form-control"  id="finish_time" placeholder="Hora fin">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Total $*</label>
    <div class="col-md-6">
      <input type="text" name="price" value="<?php echo $user->price; ?>" class="form-control" id="price" placeholder="Total $">
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