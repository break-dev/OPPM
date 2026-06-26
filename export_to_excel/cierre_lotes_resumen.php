<?php
  header('Content-type: application/vnd.ms-excel; charset=UTF-8');
  header('Content-Disposition: attachment;filename=Cierre de Lotes - Resumen.xls');
  header('Pragma: no-cache');
  header('Expires: 0');

  include('../cnx/cnx.php');
  include('../global/variables.php');

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

        ?>

          <font size = "3"><b>
            CIERRE DE LOTES
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
                <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 35px;">
                  N°
                </th>

                <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
                  Lote
                </th>

                <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 105px;">
                  Ticket Balanza
                </th>

                <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
                  Fecha Ingreso a Balanza
                </th>

                <th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                  Información Guías
                </th>

                <th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                  Proveedor Minero
                </th>

                <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
                  Observación
                </th>

                <th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                  Información de Pesos (Kg)
                </th>
              </tr>

              <tr style="font-size: 12px;">
                <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                  Remitente
                </th>

                <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                  Transportista
                </th>

                <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
                  DNI/RUC
                </th>

                <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
                  Razón Social
                </th>

                <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
                  Fecha Peso Inicial
                </th>

                <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
                  Fecha Peso Final
                </th>

                <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                  Bruto
                </th>

                <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                  Tara
                </th>

                <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                  Neto
                </th>
              </tr>
            </thead>

            <tbody id="tbl_detalle">

              <?php
                $d = 1;
                $id_lote = 0;
                $html = '';

                // Query para obtener el tipo: "Recepción de Mineral"
                  $q_validacion = " SELECT DISTINCT
                                           V.Id,
                                           MD5(V.Id) AS ID_MD5,
                                           V.lote_id_lote,
                                           V.lote_cod_lote,
                                           /*V.lote_num_ticket,*/
                                           V.lote_ticket_orden,
                                           CRL.num_ticketbalanza,
                                           V.guias_ticketbalanza,
                                           DATE(V.lote_pesoinicial_fechahoraregistro) AS FECHA_INGRESOBALANZA,
                                           V.balanza_placa,
                                           V.balanza_placa2,
                                           CL_T.documento AS TRANSPORTISTA_RUC,
                                           UPPER(CL_T.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,

                                           TV.descripcion AS TIPO_VEHICULO,

                                           CD.dni_licencia AS CONDUCTOR_DNI,
                                           CD.nombres AS CONDUCTOR_NOMBRES,

                                           V.lote_id_tipocarga,
                                           TC.descripcion AS TIPO_CARGA,

                                           V.lote_id_zonaorigen,
                                           ZO.descripcion AS ZONA_ORIGEN,

                                           V.lote_id_proveedorminero,
                                           CL.documento AS PROVEEDORMINERO_RUC,
                                           UPPER(CL.razon_social) AS PROVEEDORMINERO_RAZONSOCIAL,
                                           CL.proveedorminero_concesion,
                                           CL.proveedorminero_codigounico,
                                           CL.proveedorminero_ubicacion,

                                           V.lote_id_encargadomuestra,
                                           UPPER(EM.nombres) AS ENCARGADO_MUESTRA,

                                           V.lote_id_producto,
                                           UPPER(P.descripcion) AS PRODUCTO,

                                           V.lote_id_tipomineral,
                                           TM.descripcion AS TIPO_MATERIAL,

                                           V.despacho_observacion,

                                           
                                           V.lote_pesoinicial_fechahoraregistro,
                                           V.lote_pesofinal_fechahoraregistro,
                                           V.lote_peso_inicial AS lote_peso_bruto,
                                           V.lote_peso_final AS lote_peso_tara,
                                           V.lote_peso_neto,
                                           V.operaciones_humedad,
                                           V.lote_peso_seco,
                                           V.unidad_capacidad,
                                           V.unidad_tara,
                                           V.unidad_idmarca,
                                           V.despacho_color,

                                           V.is_cerrado,
                                           V.cerrado_fechahoraregistro,
                                           V.cerrado_usuarioregistro,

                                           V.is_cerradolote,
                                           V.cerradolote_fechahoraregistro,
                                           V.cerradolote_usuarioregistro,

                                           V.guiaremitente_serie,
                                           V.guiaremitente_numero,
                                           V.guiatransportista_serie,
                                           V.guiatransportista_numero

                                           /*GR.cGuia_serie,
                                           GR.cGuia_Numero,
                                           GR.cGuia_Serie_Transportista,
                                           GR.cGuia_Numero_Transportista*/

                                      FROM despachos_primertramo_validaciondatos V
                                           LEFT JOIN transporte T ON V.balanza_placa = T.cplaca
                                           INNER JOIN tb_clientes CL_T ON T.id_Transportista = CL_T.Id
                                           INNER JOIN tbconfig_tipovehiculo TV ON T.id_tipovehiculo = TV.Id
                                           INNER JOIN tbconfig_conductores CD ON V.guias_idchofer = CD.Id
                                           LEFT JOIN tbconfig_zonaorigen ZO ON V.lote_id_zonaorigen = ZO.Id
                                           LEFT JOIN tb_clientes CL ON V.lote_id_proveedorminero = CL.Id
                                           LEFT JOIN tbconfig_encargadosmuestra EM ON V.lote_id_encargadomuestra = EM.Id
                                           LEFT JOIN tbconfig_tipomineral TM ON V.lote_id_tipomineral = TM.Id
                                           LEFT JOIN tbconfig_tipocarga TC ON V.lote_id_tipocarga = TC.Id
                                           LEFT JOIN tbconfig_producto P ON V.lote_id_producto = P.Id
                                           /*LEFT JOIN guia_remision_detalle GRD ON V.Id = GRD.id_despachos_primertramo_validaciondatos
                                           LEFT JOIN guia_remision GR ON GRD.id_Guia_remision = GR.id_Guia_remision*/
                                           LEFT JOIN consolidado_lotes_cierrecontable CRL ON V.Id = CRL.id_registro
                                             AND CRL.id_tipoingreso = 1
                                     WHERE V.guiaremitente_serie IS NOT NULL";

                if (strlen($arr_lotes) > 0){
                  $q_validacion .= "   AND V.lote_cod_lote IN (".$arr_lotes.")";
                }
                else{
                  $q_validacion .= "   AND DATE(V.lote_pesoinicial_fechahoraregistro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
                }

                $q_validacion .= " ORDER BY V.lote_cod_lote, /*V.lote_num_ticket, V.lote_ticket_orden*/ V.guias_ticketbalanza";

                if ($res_validacion = mysqli_query($enlace, $q_validacion)){
                  if (mysqli_num_rows($res_validacion) > 0) {
                    while($row_validacion = mysqli_fetch_array($res_validacion)){
                      $html .= '<tr id="tr_detalle_'.$d.'" style="font-size: 14px;">';
                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
                      $html .= '    '.$d;
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
                      $html .= '    '.$row_validacion["lote_cod_lote"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff; min-width: 60px;">';
                      $html .= '    '.$row_validacion["num_ticketbalanza"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
                      $html .= '    <div class="d-flex">';
                      $html .= '      '.$row_validacion["FECHA_INGRESOBALANZA"];
                      $html .= '    </div>';
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
                      $html .= '    '.$row_validacion["guiaremitente_serie"].'-'.$row_validacion["guiaremitente_numero"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
                      $html .= '    '.$row_validacion["guiatransportista_serie"].'-'.$row_validacion["guiatransportista_numero"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
                      $html .= '    '.$row_validacion["PROVEEDORMINERO_RUC"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
                      $html .= '    '.$row_validacion["PROVEEDORMINERO_RAZONSOCIAL"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
                      
                      if (strlen($row_validacion["despacho_observacion"]) == 0 && strlen($row_validacion["lote_ticket_orden"]) > 0){
                        $html .= 'PARTE '.$row_validacion["lote_ticket_orden"];
                      }
                      else{
                        $html .= $row_validacion["despacho_observacion"];
                      }

                      $html .= '</textarea>';
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      $html .= '    <div class="d-flex">';
                      $html .= '      '.substr($row_validacion["lote_pesoinicial_fechahoraregistro"], 0, 10);
                      $html .= '    </div>';
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      $html .= '    <div class="d-flex">';
                      $html .= '      '.substr($row_validacion["lote_pesofinal_fechahoraregistro"], 0, 10);
                      $html .= '    </div>';
                      $html .= '  </td>';

                      $html .= '  <td id="td_pesobruto_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; min-width: 110px;">';
                      $html .= '      '.$row_validacion["lote_peso_bruto"];
                      $html .= '  </td>';

                      $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; min-width: 110px;">';
                      $html .= '      '.$row_validacion["lote_peso_tara"];
                      $html .= '  </td>';

                      $html .= '  <td id="td_pesoneto_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
                      $html .= '    '.number_format($row_validacion["lote_peso_neto"], 0, '.', ',');
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