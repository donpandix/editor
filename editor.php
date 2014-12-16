<?php
/**
 *	Editor de código fuente de archivos existentes en el servidor
 *  @autor Cesar Gonzalez Molina, twitter: @donpandix, email: hello@cesarg.cl
 *	@version: 1.0
 *
 *	TODO: descarga de archivos y creación de nuevos en línea
 */

$archivoEditable 	= false;
$contentFile		= '';
$fileUrl			= '';

# En caso de modificación del archivo actualiza la información
if ( isset($_POST['txt_update_file']) ) {
	if (file_exists ($_POST['file_url'])) {
		$myfile 	 = fopen($_POST['file_url'], "w") or die("Unable to open file!");
		fwrite ($myfile, $_POST['txt_update_file']);fclose($myfile);
	}
}

# carga el contenido del archivo en la caja de texto 
if (isset($_POST['file_url'])) {
	if (file_exists ($_POST['file_url']) ) {
		$contentFile = implode('', file($_POST['file_url']));
		$archivoEditable = true;
		$fileUrl = $_POST['file_url'];
	}
}
 
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modificador de contenido</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css">
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		<style>
			#coding {
				font-family: 'Droid Sans Mono' ;
				font-weight: 400; 
				width:100%;
				height:400px;
				font-size:0.9em;
			}
		</style>
	</head>
	<body>
		<div class="well">
			<form method="POST" action="" style="display:block;max-width:800px;margin:0px auto 0px auto;" >
				Archivo&nbsp;&nbsp;<input name="file_url" value="" style="width:300px;" > &nbsp;<button type="submit" class="btn btn-sm btn-success">Editar</button> 
			</form>
		</div>
		<?php if ($archivoEditable != false) { ?>
			<div style="display:block;max-width:800px;margin:40px auto 0px auto;">
				<h3>Editando archivo : <a href="<?php echo $fileUrl ?>" target="_blank"><?php echo $fileUrl ?></a></h3>
				<form method="POST" action="" >
					<textarea id="coding" name="txt_update_file"><?php echo $contentFile ?></textarea><br>
					<input type="hidden" name="file_url" value="<?php echo $fileUrl ?>" />
					<div style="text-align:right;">
						<button type="submit" class="btn btn-sm btn-success">Guardar</button>
					</div>
				</form>
			</div>
		<?php } ?>
		<hr>
		<footer>
        <p style="text-align:right;margin-right:40px;font-size:0.8em;">Cesar Gonzalez 2014, <a href="http://twitter.com/donpandix" target="_blank">@donpandix</a></p>
      </footer>
	</body>
</html>
