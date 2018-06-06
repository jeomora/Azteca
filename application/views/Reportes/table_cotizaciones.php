<style type="text/css">
	.font{
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		color: #000;
		}
		.row.col-sm-12.tblm {
		    margin-left: -8rem;
		    background-color: white;
		    width: 100vw;
		}
</style>
<?php 
if(!$this->session->userdata("username")){
	redirect("Compras/Login", "");
}
?>

<div class="row col-sm-12">
	<label>USUARIO: </label> <?php echo $user['username'] ?> <br>
	<label>FECHA: </label> <?php echo $fecha ?> <br>
	<label>SEMANA: </label> <?php echo $semana ?>
</div>
