<?php
  header('Content-type: application/vnd.ms-excel; charset=UTF-8');
  header('Content-Disposition: attachment;filename=Resumen de Despacho de Mineral.xls');
  header('Pragma: no-cache');
  header('Expires: 0');

  include('../cnx/cnx.php');
  include('../global/variables.php');

  // Obtener Peso Seco en función a la Humedad
    function f_GetPesoSeco($tmh, $h2o){
      if (strlen(trim($tmh)) > 0 && strlen(trim($h2o)) > 0){
        // Obtiene factor de humedad
          $factor = (100 - $h2o) / 100;

        // Obtiene Peso Seco
          $peso_seco = $tmh * $factor;
      }
      else{
        $peso_seco = '';
      }

      return $peso_seco;
    }

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
  </head>

  <body class="nav-md footer_fixed">
    <div class="container body">
      <div class="main_container">
        <?php

          // Recuperando las variables enviadas
            $fecha_inicio = $_GET["fecha_inicio"];
            $fecha_fin = $_GET["fecha_fin"];
            $filtro_destino = $_GET["filtro_destino"];
            $filtro_destino = (($filtro_destino == 'null') ? '' : $filtro_destino);
            
            $filtro_lote = $_GET["filtro_lote"];
            $filtro_lote = explode(',', $filtro_lote);

            // Setea Filtro de Lotes
              $l = 0;
              $arr_lotes = '';

              if(isset($filtro_lote) && is_array($filtro_lote)) {
                while ($l < count($filtro_lote)){
                  if (strlen($filtro_lote[$l]) > 0){
                    $arr_lotes .= "'".$filtro_lote[$l]."', ";
                  }

                  $l ++;
                }

                if (strlen($arr_lotes) > 0){
                  $arr_lotes = substr($arr_lotes, 0, -2);
                }
              }

            $filtro_codigodespacho = $_GET["filtro_codigodespacho"];
            $filtro_codigodespacho = explode(',', $filtro_codigodespacho);

            // Setea Filtro de Códigos de Despacho
              $l = 0;
              $arr_codigosdespacho = '';

              if(isset($filtro_codigodespacho) && is_array($filtro_codigodespacho)) {
                while ($l < count($filtro_codigodespacho)){
                  if (strlen($filtro_codigodespacho[$l]) > 0){
                    $arr_codigosdespacho .= "'".$filtro_codigodespacho[$l]."', ";
                  }

                  $l ++;
                }

                if (strlen($arr_codigosdespacho) > 0){
                  $arr_codigosdespacho = substr($arr_codigosdespacho, 0, -2);
                }
              }


        ?>

          <font size = "3"><b>
            RESUMEN DE DESPACHO DE MINERAL
          </b></font>

          <br/>

          <font size = "2">
            Fecha de inicio: <?php echo $fecha_inicio; ?>
          </font>
          <br/>
          <font size = "2">
            Fecha de fin: <?php echo $fecha_fin; ?>
          </font>

          <br/>
          <br/>

          <table class="table table-bordered table-hover">
            <thead>
              <tr style="font-size: 12px;">
                <th class="sticky" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 32px;">
                  N°
                </th>

                <th rowspan="2" class="sticky-2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 66px;">
                  Lote
                </th>

                <th rowspan="2" class="sticky-3" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 70px;">
                  Parte
                </th>

                <th rowspan="2" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
                  Responsable Despacho Lote
                </th>

                <th rowspan="2" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 30px;">
                  Estado
                </th>

                <th colspan="5" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                  Información Básica Despacho
                </th>

                <th colspan="5" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                  Información del Transporte
                </th>

                <th rowspan="2" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px; border-top-right-radius: 15px;">
                  Peso Salida AUM (TMH)
                </th>
              </tr>

              <tr style="font-size: 12px;">
                <th class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
                  Código Despacho
                </th>

                <th class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
                  Destino
                </th>

                <th class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
                  Modalidad Envío
                </th>

                <th class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                  Fecha Real Salida AUM
                </th>

                <th class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                  Hora Real Salida AUM
                </th>

                <th class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 30px;">
                  Coordinador Transporte
                </th>

                <th class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 350px;">
                  Empresa Transportes
                </th>

                <th class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                  Tipo Vehículo
                </th>

                <th class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                  Placa 1
                </th>

                <th class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                  Placa 2
                </th>
              </tr>
            </thead>

            <tbody id="tbl_detalle">

              <?php
                $d = 1;
                $html = '';

                $q_datos = "SELECT DL.Id,
                                   DL.cod_lote,
                                   DL.num_parte,
                                   P.fechaestimada_despacho,
                                   HED.hora_estimada,
                                   DL.guias_fecha,
                                   US.usu_usuario,
                                   PL.nombre_comercial AS DESTINO,
                                   PD.id_planta,

                                   CASE WHEN PD.id_planta = 3
                                     THEN (SELECT DISTINCT ME_x.descripcion
                                             FROM despachos_primertramo_validaciondatos V
                                                  INNER JOIN tbconfig_modalidadenvio ME_x ON V.despacho_id_modalidadenvio = ME_x.Id
                                            WHERE V.lote_cod_lote = DL.cod_lote)
                                   ELSE ME.descripcion END AS MODALIDAD_ENVIO,

                                   PD.codigo_despacho,
                                   UN.coordinador_nombres AS COORDINADOR_TRANSPORTE,
                                   TR.razon_social AS TRANSPORTISTA,
                                   TV.descripcion AS TIPO_VEHICULO,
                                   UN.cplaca AS PLACA,
                                   UN2.cplaca AS PLACA2,
                                   IFNULL(DL.peso_tara, 0),
                                   IFNULL(DL.peso_bruto, 0),
                                   ABS(IFNULL(DL.peso_bruto, 0) - IFNULL(DL.peso_tara, 0)) AS PESO_NETO,
                                   PD.codigo_planta,

                                   CASE WHEN PD.id_planta = 3
                                     THEN PD.codigo_planta
                                   ELSE DL.llegadaplanta_codigoplanta END AS llegadaplanta_codigoplanta,

                                   DL.llegadaplanta_fechaingreso,
                                   DL.llegadaplanta_codigopesaje,
                                   DL.llegadaplanta_pesobrutoplanta,
                                   DL.llegadaplanta_pesotaraplanta,
                                   DL.llegadaplanta_pesonetoplanta,
                                   DL.llegadaplanta_humedadplanta,
                                   DL.llegadaplanta_pesoseco,
                                   DL.llegadaplanta_ticketpesoplanta,
                                   DL.llegadaplanta_tickethumedadplanta,
                                   DL.llegadaplanta_iscierre,
                                   DL.llegadaplanta_iscierre_fechahoraregistro,
                                   DL.llegadaplanta_iscierre_usuarioregitro,

                                   (SELECT CI.fechahora_salida
                                    FROM controlingresovehiculo CI
                                     WHERE CI.placa = UN.cplaca
                                      AND CI.dFechaIngreso BETWEEN U.fecha_ingresoplanta AND P.fechaestimada_despacho
                                    ORDER BY CI.fechahora_salida DESC
                                    LIMIT 1) AS FECHAHORA_SALIDA

                             FROM despachos_segundotramo_programacion_detalle PD
                                  INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
                                  INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
                                  INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
                                    AND PD.cod_lote = DL.cod_lote
                                  INNER JOIN transporte UN ON U.id_unidad = UN.id_transporte
                                  LEFT JOIN transporte UN2 ON U.id_unidad2 = UN2.id_transporte
                                  INNER JOIN tb_clientes TR ON UN.id_Transportista = TR.Id
                                  INNER JOIN tbconfig_plantas PL ON PD.id_planta = PL.Id
                                  LEFT JOIN tbconfig_modalidadenvio ME ON PD.id_modalidadenvio = ME.Id
                                  INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
                                  INNER JOIN tbconfig_tipovehiculo TV ON UN.id_tipovehiculo = TV.Id
                                  INNER JOIN tb_usuario US ON DL.id_responsabledespacho = US.id_empleado
                                  LEFT JOIN despachos_segundotramo_programacion_horaestimadadespacho HED ON PD.codigo_despacho = HED.codigo_despacho
                            WHERE DL.is_complemento = 0";

                if (strlen($arr_lotes) > 0 || strlen($arr_codigosdespacho) > 0){
                  if (strlen($arr_lotes) > 0){
                    $q_datos .= "   AND DL.cod_lote IN (".$arr_lotes.")";
                  }

                  if (strlen($arr_codigosdespacho) > 0){
                    $q_datos .= "   AND PD.codigo_despacho IN (".$arr_codigosdespacho.")";
                  }
                }
                else{
                  $q_datos .= "   AND DATE(P.fechaestimada_despacho) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

                  if ($filtro_destino != 99){
                    $q_datos .= "   AND PD.id_planta = ".$filtro_destino;
                  }
                }

                $q_datos .= " ORDER BY DL.cod_lote, DL.num_parte";

                if ($res_datos = mysqli_query($enlace, $q_datos)){
                  if (mysqli_num_rows($res_datos) > 0) {
                    $estado = 1;

                    while($row_datos = mysqli_fetch_array($res_datos)){
                      // Asignando un color único a cada placa
                        $placa = $row_datos["PLACA"];

                        if (!isset($colores_placas[$placa])) {
                          $colores_placas[$placa] = $arr_colores[$color_index % count($arr_colores)];

                          $color_index++;
                        }

                        $color_fila = $colores_placas[$placa];

                      $html .= '<tr style="font-size: 14px; cursor: pointer; background-color: '.$color_fila.'">';

                      $html .= '  <td class="row-sticky" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; cursor: pointer; background-color: #ffffff;">';
                      $html .= '    '.$d;
                      $html .= '  </td>';

                      $html .= '  <td class="row-sticky-2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer; background-color: #ffffff; font-weight: bold;">';
                      $html .= '    '.$row_datos["cod_lote"];
                      $html .= '  </td>';

                      $html .= '  <td class="row-sticky-3" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer; background-color: #ffffff;">';
                      $html .= '    '.((strlen($row_datos["num_parte"]) == 0) ? '' : 'PARTE '.$row_datos["num_parte"]);
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.$row_datos["usu_usuario"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.((strlen($row_datos["FECHAHORA_SALIDA"]) == 0) ? 'PROGRAMADO' : 'DESPACHADO');
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.$row_datos["codigo_despacho"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.$row_datos["DESTINO"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.$row_datos["MODALIDAD_ENVIO"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.explode(' ', $row_datos["FECHAHORA_SALIDA"])[0];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.explode(' ', $row_datos["FECHAHORA_SALIDA"])[1];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.$row_datos["COORDINADOR_TRANSPORTE"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.$row_datos["TRANSPORTISTA"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.$row_datos["TIPO_VEHICULO"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer; font-weight: bold;">';
                      $html .= '    '.$row_datos["PLACA"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.$row_datos["PLACA2"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
                      $html .= '    '.number_format($row_datos["PESO_NETO"], 3, '.', '');
                      $html .= '  </td>';

                      $html .= '</tr>';

                      $d ++;
                    }
                  }
                }

                echo $html;

              ?>

            </tbody>
          </table>
      </div>
    </div>
  </div>
</html>