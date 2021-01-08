<?php 
	include("../../conexi.php");
	$link = Conectarse();
	
	$id_usuarios = $_POST['id_usuarios'];
	$id_ventas = $_POST['id_ventas'];
	$fecha_cancelacion = date("d/m/Y");
	$hora_cancelacion = date("H:i:s");
	$motivo = $_POST['motivo'];
	
	$cancela_venta = "UPDATE ventas SET 
		estatus_ventas = '{$_POST["estatus_ventas"]}',
		datos_cancelacion = 
		'Usuario {$_COOKIE["nombre_usuarios"]}, <br>
		Fecha: $fecha_cancelacion, <br>
		Hora: $hora_cancelacion, <br>
		Motivo: $motivo'
	WHERE id_ventas = '{$_POST["id_ventas"]}'";
	
	
	$result_cancela = mysqli_query($link,$cancela_venta);
	 
	$respuesta["result_cancela"] = $result_cancela;
	$respuesta["mensaje_cancela"] = mysqli_error($link);
	
	
	//Consulta de Productos por venta
	
	$consulta_ventas = "SELECT * FROM ventas LEFT JOIN ventas_detalle USING(id_ventas)
	WHERE id_ventas = '{$_POST["id_ventas"]}'";
	
	
	$result_ventas = mysqli_query($link,$consulta_ventas);
	
	if($result_ventas){
		while($fila_ventas = mysqli_fetch_assoc($result_ventas)){
			$listaProductos[] = $fila_ventas;
		}
	}
	
	foreach($listaProductos as $indice => $producto){
		
		//INSERTA movimientos
		
		//actualiza existencias
		
		$update_existencia = "UPDATE productos 
		SET existencia_productos = existencia_productos + '{$producto["cantidad"]}'
		WHERE id_productos = '{$producto["id_productos"]}'	"; 
		
		$result_existencia = mysqli_query( $link, $update_existencia );
		
		$respuesta["result_existencia"] = $result_existencia ;
		
		
		
		//actualiza existencias
		
		$respuesta["existencias"][] = $existencia_anterior;
		
		
	}
	
	echo json_encode($respuesta);
?>