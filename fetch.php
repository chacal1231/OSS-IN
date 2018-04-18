<?php
//fetch.php
include 'inc/config.php';
header('Content-Type: application/json');
$request = mysqli_real_escape_string($link, $_POST["query"]);
$query = "SELECT * FROM prov WHERE nombre LIKE '%".$request."%'";

$result = mysqli_query($link, $query);

$data = array();

if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_assoc($result))
 {
  $data[] = $row["nombre"];
 }
 echo json_encode($data);
}
?>