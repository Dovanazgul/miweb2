<?php
include("conexion.php");

if(isset($_GET['taller'])){
    $taller = $conn->real_escape_string($_GET['taller']);
    $sql = "SELECT grupo FROM talleres WHERE taller='$taller' ORDER BY grupo ASC";
    $res = $conn->query($sql);

    while($row = $res->fetch_assoc()){
        echo "<option value='".$row['grupo']."'>".$row['grupo']."</option>";
    }
}
?>
