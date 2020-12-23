<?php 
	header("Content-Type: application/json");
	include('../../conexi.php');
	$link = Conectarse();
	$respuesta = array();
	$consulta = "SELECT *,
	ROUND(existencia_productos / piezas) AS existencia_cajas
	FROM productos 
	LEFT JOIN departamentos USING (id_departamentos) 
	
	WHERE  id_productos = '{$_GET["id_productos"]}'";    
	
	
	
	$result = mysqli_query($link,$consulta);
	if(!$result){
        die("Error en $consulta" . mysqli_error($link) );
		}else{
		$num_rows = mysqli_num_rows($result);
		if($num_rows != 0){
			$respuesta["encontrado"] = 1;
			while($row = mysqli_fetch_assoc($result)){
				$respuesta["fila"] = $row;        
				
			}
		}
		else{
			
		}
	}
	
    echo json_encode($respuesta);
?>
