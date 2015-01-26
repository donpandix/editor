<?php
/**
 *	Editor de código fuente de archivos existentes en el servidor
 *  @autor Cesar Gonzalez Molina, twitter: @donpandix, email: hello@cesarg.cl
 *	@version: 1.2
 *
 *	Novedad version 1.1 : panel izquierdo de navegación y edición de archivos
 * *	Novedad version 1.2 : qyuita el link para modificar este archivo.
 */

 
$archivoEditable 	= false;
$contentFile		= '';
$fileUrl			= '';
$pathBase			= implode('/', explode('\\', dirname(__FILE__)));
 
if (isset($_POST['path_url'])) {

	$_POST['path_url'] = implode('/', explode('\\', $_POST['path_url']));

	if (substr($_POST['path_url'], -1) == '/')
		$_POST['path_url'] = substr($_POST['path_url'], 0, strlen($_POST['path_url']) - 1);

	if (substr($_POST['path_url'], -2) == '..') {
		$temp = explode('/', $_POST['path_url']);
		array_pop($temp);array_pop($temp);
		$_POST['path_url'] = implode('/', $temp);
	}

	$pathBase = $_POST['path_url'];	
}



# En caso de modificación del archivo actualiza la información
if ( isset($_POST['txt_update_file']) ) {
	if (file_exists ($pathBase . '/' . $_POST['file_url'])) {
		$myfile 	 = fopen($pathBase . '/' . $_POST['file_url'], "w") or die("Unable to open file!");
		fwrite ($myfile, $_POST['txt_update_file']);
		fclose($myfile);
	}
}

# carga el contenido del archivo en la caja de texto 
if (isset($_POST['file_url'])) {
	if (file_exists ($pathBase .'/'. $_POST['file_url']) ) {
		$contentFile = implode('', file($pathBase . '/' . $_POST['file_url']));
		$archivoEditable = true;
		$fileUrl = $_POST['file_url'];
	}
}



# Clase de listado de archivos
class fileEditor {

	private $rootPath = '';

	function __construct ( $dir = '' ) {
		$this->rootPath = $dir;
	}

	function getFiles () {

		global $pathBase;

		$directorio 	= opendir($this->rootPath ); 
		
		while ($archivo = readdir($directorio)) {
			if ( ! is_dir( $pathBase .'\\' . $archivo ) && ( __FILE__ != ($pathBase .'\\'. $archivo) ) ) {
				if (implode('/', explode('\\',dirname(__FILE__))) . '\\' . basename(__FILE__) != $pathBase .'\\'. $archivo) {
					echo '<a href="javascript:triggerAction(\'' . $archivo . '\')"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> &nbsp;'.$archivo.'</a><br>'; 
				} else {
					echo '<span class="glyphicon glyphicon-file" aria-hidden="true"></span> &nbsp;'.$archivo.'<br>'; 
				}
			} else {
				if (is_dir( $pathBase .'\\' . $archivo )) {
					echo '<a href="javascript:triggerNav(\'' . $archivo . '\')"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> &nbsp;'.$archivo.'</a><br>'; 
				} else {
					echo $archivo . '<br>';	
				}
			}
		}
		  
		closedir($directorio); 
	}

}

$myEditor = new fileEditor( $pathBase );
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

			body {
				font-size:1.2em;
			}

			#coding {
				font-family: 'Droid Sans Mono' ;
				font-weight: 400; 
				width:100%;
				height:400px;
				font-size:0.8em;
			}

		</style>
		<script>

			function triggerAction ( url ) {
				$("#txt_file").val( url );
				$("#frm_action").submit();
			}

			function triggerNav ( pathFolder ) {
				$("#pathurl").val( '<?php echo $pathBase . '/' ?>' + pathFolder);
				$("#frm_path").submit();
			}	

		</script>
	</head>
	<body>

		<div class="well">
			<form method="POST" id="frm_path" action="" style="display:block;margin:0px;" >
				Ruta&nbsp;&nbsp;<input name="path_url" id="pathurl" value="<?php echo  $pathBase ?>" style="width:300px;" > &nbsp;<button type="submit" class="btn btn-sm btn-success">Ir</button> 
			</form>
		</div>
		
		<div class="row">
			
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading"><?php echo $pathBase ?></div>
					<div class="panel-body">
						<?php $myEditor->getFiles(); ?>
					</div>
					<div class="panel-footer">Archivo seleccionado : </div>
				</div>
			</div>

			<div class="col-md-8">
				<?php if ($archivoEditable != false) { ?>
					<div style="display:block;max-width:100%;margin:0px auto;">
						<form method="POST" action="" >
							<textarea id="coding" name="txt_update_file"><?php echo $contentFile ?></textarea><br>
							<input type="hidden" name="file_url" value="<?php echo $fileUrl ?>" />
							<input type="hidden" name="path_url" value="<?php echo $pathBase ?>" >

							<div class="row">
								<div class="col-md-9">
									Editando archivo : <strong><?php echo $fileUrl ?></strong>
								</div>
								<div class="col-md-3" style="text-align:right;">
									<button type="submit" class="btn btn-sm btn-success">Guardar</button>	
								</div>
							</div>

						</form>
					</div>
				<?php } ?>
			</div>

		</div>

		<!-- acciones ocultas -->

			<form method="POST" action="" id="frm_action" style="display:none;" >
				<input name="file_url" value="" id="txt_file" >
				<input name="path_url" value="<?php echo $pathBase ?>" >
			</form>


		<hr>
		<footer>
        <p style="text-align:right;margin-right:20px;font-size:0.8em;">Cesar Gonzalez <?php echo date('Y') ?>, <a href="http://twitter.com/donpandix" target="_blank">@donpandix</a></p>
      </footer>
	</body>
</html>
