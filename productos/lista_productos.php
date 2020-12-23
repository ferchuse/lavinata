<?php 
	header("Content-Type: application/json");
	include('../conexi.php');
	$link = Conectarse();
	$arrResult = array();
	$consulta = "SELECT *,
	COALESCE ( vendidos, 0 ) AS vendidos ,
	existencia_productos / piezas AS existencia_cajas
	FROM productos 
	
	
	LEFT JOIN departamentos USING (id_departamentos) 
	LEFT JOIN (
	SELECT id_ventas, id_productos, ROUND(SUM( cantidad )) AS vendidos
	FROM ventas_detalle 
	LEFT JOIN ventas USING ( id_ventas )
	WHERE estatus_ventas = 'PAGADO'
	GROUP BY id_productos ) AS t_vendidos 
	USING (id_productos)
	
	
	
	WHERE 1";    
	if($_GET["id_departamentos"] != '') {        
		$consulta.= " AND  id_departamentos = '{$_GET["id_departamentos"]}'";
	}
	if($_GET["existencia"] != '') {        
		$consulta.= " AND existencia_productos < min_productos";
	} 
	
	if($_GET["descripcion_productos"] != '') {        
		$consulta.= " AND descripcion_productos LIKE '%{$_GET["descripcion_productos"]}%'";
	} 
	
	
	$consulta.= "  ORDER BY {$_GET["orden"]} {$_GET["asc"]}";
	$result = mysqli_query($link,$consulta);
	if(!$result){
        die("Error en $consulta" . mysqli_error($link) );
		}else{
		$num_rows = mysqli_num_rows($result);
		if($num_rows != 0){
			while($row = mysqli_fetch_assoc($result)){
				$arrResult[] = $row;        
				
			}
			}else{
			
		}
	}
    echo json_encode($arrResult);
?>
