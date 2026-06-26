<?php

	session_start();
	ini_set('memory_limit', '4096M');

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares.php');


	$q_tipodocumento = "SELECT *
                      FROM tb_ticket_import
                      ORDER BY id";

  if ($res_tipodocumento = mysqli_query($enlace, $q_tipodocumento)){
    if (mysqli_num_rows($res_tipodocumento) > 0) {
      while($row_tipodocumento = mysqli_fetch_array($res_tipodocumento)){
      	if ($resultado = file_get_contents($row_tipodocumento["url"])){
      		echo 'Generado: '.$row_tipodocumento['id'];
      		echo "<br>";
      	}
      	else{
      		echo 'Error: '.$row_tipodocumento["url"];
      	}


      }
    }
  }

?>
