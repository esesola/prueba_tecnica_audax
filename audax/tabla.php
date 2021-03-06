<?php

include('config.php');


  
if ($mysqli->connect_error) {
  echo 'Errno: '.$mysqli->connect_errno;
  echo '<br>';
  echo 'Error: '.$mysqli->connect_error;
  exit();
}
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$STMT_QUERY_BASE = "SELECT TCON.ID_CONTRATO, TCLI.ID_CLIENTE, TCLI.NOMBRE FROM CONTRACTS TCON INNER JOIN CUSTOMERS TCLI ON TCON.ID_CLIENTE = TCLI.ID_CLIENTE";
$stmt_query = $STMT_QUERY_BASE;
if ($queries['filter']){
    $stmt_query = $stmt_query . " WHERE TCLI.ID_CLIENTE LIKE ? ";
}
$stmt_query = $stmt_query . " LIMIT 1000";
$array_rows = [];
if ($stmt = $mysqli->prepare($stmt_query)) {
    if ($queries['filter']){
        $param = "%" . $queries['filter'] . "%";
        $stmt->bind_param('s', $param);
    }
    $stmt->execute();
    $stmt->bind_result($id_contrato_db, $id_cliente_db, $nombre_cliente);
    while($stmt->fetch()){
        $row = ["idContrato" => $id_contrato_db, "idCliente" =>$id_cliente_db, "nombreCliente"=> $nombre_cliente];
        array_push($array_rows, $row);
    };
    $stmt->close();
} 

$mysqli->close();

$data = ["tabla" => $array_rows];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

?>