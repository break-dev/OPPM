<?php

  session_start();

  include('cnx/cnx.php');
  include('global/variables.php');
  include('global/auxiliares.php');

  if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo $favicon; ?>" type="image/png"/>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <title><?php echo $nom_app; ?> | Gestión de Muestras</title>

    <script type="text/javascript">
      let itemlote_Selected = 0;
      let codlote_Selected = 0;

      let itemgrupo_Selected = 0;
      let codgrupo_Selected = 0;
    </script>

    <style>

    </style>
  </head>

  <body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
    <div class="container-fluid">
      <div class="row">
        <!-- Llamando a Navbar -->
        <?php echo $navbar_maintop; ?>

        <div class="row">
          <!-- Menús principales -->
          <div id="div_menu1" class="col-md-1 col-sm-1 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #DEDEDE;">
            
          </div>

          <div class="col-md-11 col-sm-11 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
            <div class="d-flex row">
              <div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px;">
                <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                  <h5>Filtros</h5>
                </div>

                <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                  <hr style="border-color: #D9D9D9;"/>
                </div>

                <div class="row" style="padding-left: 30px; margin-top: -5px; margin-bottom: 10px; font-size: 13px;">
                  <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 2px;">
                    <div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                      <div class="row" style="padding-left: 10px; padding-right: 10px;">
                        <h6 style="font-size: 14px;">Por Fecha de creación</h6>
                      </div>

                      <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                        <hr style="border-color: #D9D9D9;"/>
                      </div>

                      <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                        <input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">

                        <input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>">
                      </div>
                    </div>
                  </div>

                  <div class="col-md-9 col-sm-9 col-xs-12" style="padding: 2px;">
                    <div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                      <div class="row" style="padding-left: 10px; padding-right: 10px;">
                        <h6 style="font-size: 14px;">Por Lotes:</h6>
                      </div>

                      <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                        <hr style="border-color: #D9D9D9;"/>
                      </div>

                      <div class="d-flex" style="margin-top: -8px; padding-left: 10px; padding-right: 10px;">
                        <div class="flex-fill">
                          <select id="filtro_lote" class="form-control select_datos" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
                            <?php

                            $q_lotes = "SELECT ccod_Lote
                                            FROM catalogolotes
                                           WHERE (YEAR(dFechaIngreso) >= 2024
                                              OR ccod_Lote IN ('AUM-2587', 'AUM-3000', 'AUM-2337', 'AUM-2585', 'AUM-2907', 'AUM-2980'))
                                          ORDER BY ccod_Lote DESC";

                            if ($res_lotes = mysqli_query($enlace, $q_lotes)) {
                              if (mysqli_num_rows($res_lotes) > 0) {
                                while ($row_lotes = mysqli_fetch_array($res_lotes)) {
                                  ?>

                                  <option value="<?php echo $row_lotes["ccod_Lote"]; ?>"><?php echo $row_lotes["ccod_Lote"]; ?></option>

                                  <?php
                                }
                              }
                            }

                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row" style="padding-left: 30px; margin-top: 5px; margin-bottom: 10px; font-size: 13px;">
                  <div class="col-md-10 col-sm-10 col-xs-12">
                    <button class="btn btn-secondary" type="button" onclick="f_LoadLotes();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
                      <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
                    </button>
                  </div>

                  <div class="col-md-2 col-sm-2 col-xs-12">
                    <button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;">
                      <b>Exportar a Excel</b>
                    </button>
                  </div>
                </div>
              </div>

              <div class="row" style="padding: 0px;">
                <div id="div_lotes" class="col-md-4 col-sm-4 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
                  <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px; margin-right: 5px;">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                      <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                        <div class="d-flex">
                          <h6>Lista de Lotes</h6>

                          <div id="wt_lotes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Cargando datos...</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                      <hr style="border-color: #D9D9D9;"/>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-y: scroll; width: 100%; height: 650px;">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr style="font-size: 12px;">
                            <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                              
                            </th>

                            <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                              Lote
                            </th>

                            <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                              Planta<br>Ingreso
                            </th>

                            <th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
                              Proveedor
                            </th>
                          </tr>

                          <tr style="font-size: 12px;">
                            <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
                              DNI/RUC
                            </th>

                            <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
                              Razón Social
                            </th>
                          </tr>
                        </thead>

                        <tbody id="tbl_lotes">
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div id="div_grupos" class="col-md-3 col-sm-3 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
                  <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px; margin-right: 5px;">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                      <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                        <div class="d-flex">
                          <h6>Detalle de Grupos: </h6>
                          <h6 id="lbl_TituloLote" style="margin-left: 5px; color: #337ab7;"></h6>

                          <div id="wt_grupos" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Cargando datos...</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                      <hr style="border-color: #D9D9D9;"/>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr style="font-size: 12px;">
                            <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                            </th>

                            <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                              Grupo
                            </th>

                            <th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
                              Cant. Muestras
                            </th>
                          </tr>

                          <tr style="font-size: 12px;">
                            <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 40px;">
                              Por<br>Grupo
                            </th>

                            <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 40px;">
                              Asignadas<br>para análisis
                            </th>
                          </tr>
                        </thead>

                        <tbody id="tbl_grupos">
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div id="div_muestras" class="col-md-5 col-sm-5 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
                  <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                      <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                        <div class="d-flex">
                          <h6>Lista de Muestras</h6>
                          <h6 id="lbl_TituloGrupo" style="margin-left: 5px; color: #337ab7;"></h6>

                          <div id="wt_muestras" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Cargando datos...</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                      <hr style="border-color: #D9D9D9;"/>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr style="font-size: 12px;">
                            <th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                            </th>

                            <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 115px;">
                              Código<br>Muestra
                            </th>

                            <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                              Análisis
                            </th>

                            <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                              Laboratorio
                            </th>

                            <th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
                              Asignado<br>para análisis
                            </th>
                          </tr>
                        </thead>

                        <tbody id="tbl_muestras">
                          
                        </tbody>
                      </table>

                      <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: -5px;">
                        <button class="btn btn-primary" type="button" onclick="f_AddNewMuestra();" style="color: #ffffff; width: 100%; font-size: 12px;">
                          <b> + Agregar Muestra</b>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Menú flotante -->
      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color: #DEDEDE; width: 20%; z-index: 10000;">
        <div class="offcanvas-header" style="background-color: #ffffff;">
          <h5 id="sb1_titulo" class="offcanvas-title" id="offcanvasExampleLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div id="div_submenu1" class="offcanvas-body" style="color: #212529;">

        </div>
      </div>
    </div>

    <!-- Ventanas modales -->


    <!-- Referenciando a JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- ECharts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

    <!-- Referenciando auxiliares -->
    <?php include('global/auxiliares_js.php'); ?>

    <!-- Funciones de Inicio -->
    <script type="text/javascript">
      function f_Init(){
        // Genera menús
          f_GetMenuPrincipal();

        // Titulo de Pantalla
          $("#nv_titulo").html('| Gestión de Muestras');

        // Carga el detalle de información
          f_LoadLotes();
      }

    </script>

    <!-- Seteando objetos Select2 -->
    <script type="text/javascript">
      // Listas para edición
        $('.select_datos').select2({
          theme: "bootstrap-5",
          width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
          placeholder: $( this ).data( 'placeholder' ),
          allowClear: true
        });
    </script>

    <!-- Funciones Principales -->
    <script type="text/javascript">
      function f_LoadLotes(){
        var _html = '';

        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
        var filtro_lotes = $("#filtro_lote").val();

        f_LoadingLotes(1);

        $("#tbl_lotes").html('');
        $("#tbl_grupos").html('');
        $("#tbl_muestras").html('');

        $.post( "apis/backend.php", { accion: "get_GestionMuestras_ListaLotes", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_lotes: filtro_lotes }, 
          function( data ) {
            if(data.estado == 1){
              $("#tbl_lotes").html(data.html);

              itemlote_Selected = 1;
              codlote_Selected = data.cod_lote;

              f_LoadItemLote(itemlote_Selected, codlote_Selected);
            }

            f_LoadingLotes(0);

          }, "json");
      }

      function f_LoadItemLote(_item, _cod_lote) {
        var _html = '';

        // Pinta selección
          f_ColorSelected_Lote(_item);

        // Seteando grupos por Lote
          f_LoadingGrupos(1);

          $("#tbl_grupos").html('');

          $.post( "apis/backend.php", { accion: "set_GestionMuestras_GrupoMuestras", cod_lote: _cod_lote },
            function( data ) {
              if(data.estado == 1){
                f_GetListaGrupos(_cod_lote);
              }

              f_LoadingGrupos(0);

          }, "json");

        itemlote_Selected = _item;
        codlote_Selected = _cod_lote;
      }

      function f_GetListaGrupos(_cod_lote, _is_selected){
        f_LoadingGrupos(1);

        $("#tbl_grupos").html('');

        $.post( "apis/backend.php", { accion: "get_GestionMuestras_ListaGrupos", cod_lote: _cod_lote },
          function( data ) {
            if(data.estado == 1){
              $("#tbl_grupos").html(data.html);

              if (_is_selected != 1){
                itemgrupo_Selected = 1;
                codgrupo_Selected = data.cod_grupo;
              }

              f_LoadItemGrupos(itemgrupo_Selected, codgrupo_Selected);
            }

            f_LoadingGrupos(0);

        }, "json");
      }

      function f_LoadItemGrupos(_item, _cod_grupo) {
        var _html = '';

        // Pinta selección
          f_ColorSelected_Grupo(_item);

        // Obteniendo la lista de Muestras por Grupo
          f_LoadingMuestras(1);

          $("#tbl_muestras").html('');

          $.post( "apis/backend.php", { accion: "get_GestionMuestras_ListaMuestras", id_grupo: _cod_grupo },
            function( data ) {
              if(data.estado == 1){
                $("#tbl_muestras").html(data.html);
              }

              f_LoadingMuestras(0);

          }, "json");

        itemgrupo_Selected = _item;
        codgrupo_Selected = _cod_grupo;
      }

      function f_ColorSelected_Lote(_item) {
        var i = 1;

        // Setea todas las filas en blanco
          $(".cls_trtbl_lotes").css('background-color', '');

        // Seteando item seleccionado
          $("#trtbl_lotes_" + _item).css('background-color', '#FFF587');

          $("#lbl_TituloLote").html($("#tritem_2_" + _item).html().trim());
      }

      function f_ColorSelected_Grupo(_item) {
        var i = 1;

        // Setea todas las filas en blanco
          $(".cls_trtbl_grupos").css('background-color', '');

        // Seteando item seleccionado
          $("#trtbl_grupos_" + _item).css('background-color', '#FFF587');

          $("#lbl_TituloGrupo").html($("#td_tblgrupos_item_2_" + _item).html().trim());
      }
    </script>

    <!-- Funciones Secundarias -->
    <script type="text/javascript">
      function f_LoadingLotes(_is_show){
        if (_is_show == 1){
          $("#wt_lotes").show();
        }
        else{
          $("#wt_lotes").hide();
        }
      }

      function f_LoadingGrupos(_is_show){
        if (_is_show == 1){
          $("#wt_grupos").show();
        }
        else{
          $("#wt_grupos").hide();
        }
      }

      function f_LoadingMuestras(_is_show){
        if (_is_show == 1){
          $("#wt_muestras").show();
        }
        else{
          $("#wt_muestras").hide();
        }
      }
    </script>

    <!-- Funciones de Grabación -->
    <script type="text/javascript">
      function f_SaveImagen(_cod_lote){
        if ($("#img_evidencia").attr('src').length == 0){
          setTimeout('f_SaveImagen(' + "'" + _cod_lote + "'" + ')', 1000);
        }
        else{
          // Guardando archivo
            var arr_imagenes = [];

            var _imagen = {
              imagen: $("#img_evidencia").attr('src')
            };

            arr_imagenes.push(_imagen);

            $.post( "apis/backend.php", { accion: "grabar_GestionMuestras_UploadEvidencias", cod_lote: _cod_lote, arr_imagenes: JSON.stringify(arr_imagenes) },
              function( data ) {
                if(data.estado == 1){
                  $("#img_view_" + _cod_lote).css('display', 'block');
                }
                else{
                  if (data.estado == 0){
                    alert("Ocurrió un error al momento de grabar la imagen.");

                    return;
                  }

                  if (data.estado == 99){
                    alert("El formato de imagen no es compatible.");

                    return;
                  }
                }

              }, "json");
        }
      }

      function f_UpdateDatos(_item, _orden_campo) {
        // Obtiene Id del registro
          var id_registro = $("#val_tblmuestras_1_" + _item).val();

        // Obtiene Valor
          var valor = $("#val_tblmuestras_" + _orden_campo + '_' + _item).val().trim();

          if (_orden_campo == 5){
            valor = (($("#val_tblmuestras_" + _orden_campo + '_' + _item).prop('checked')) ? 1 : 0);
          }

        // Si es Check valida el registro de demás datos
          if (_orden_campo == 5){
            var codigo_muestra = $("#val_tblmuestras_2_" + _item).val();
            var id_analisis = $("#val_tblmuestras_3_" + _item).val();
            var id_laboratorio = $("#val_tblmuestras_4_" + _item).val();

            if (codigo_muestra == null){
              alert("Debe ingresar el Código de Muestra.");

              $("#val_tblmuestras_5_" + _item).prop('checked', false);

              return;
            }
            if (codigo_muestra.length == 0){
              alert("Debe ingresar el Código de Muestra.");

              $("#val_tblmuestras_5_" + _item).prop('checked', false);

              return;
            }

            if (id_analisis == null){
              alert("Debe seleccionar el Análisis.");

              $("#val_tblmuestras_5_" + _item).prop('checked', false);

              return;
            }
            if (id_analisis.length == 0){
              alert("Debe seleccionar el Análisis.");

              $("#val_tblmuestras_5_" + _item).prop('checked', false);

              return;
            }

            if (id_laboratorio == null){
              alert("Debe seleccionar el Laboratorio a donde llevará la muestra.");

              $("#val_tblmuestras_5_" + _item).prop('checked', false);

              return;
            }
            if (id_laboratorio.length == 0){
              alert("Debe seleccionar el Laboratorio a donde llevará la muestra.");

              $("#val_tblmuestras_5_" + _item).prop('checked', false);

              return;
            }
          }

        // Actualizando registros
          $.post( "apis/backend.php", { accion: "update_GestionMuestras_InformacionMuestras", id_registro: id_registro, orden_campo: _orden_campo, valor: valor },
            function( data ) {
              if(data.estado == 1){
                if (_orden_campo == 5){
                  f_GetListaGrupos(codlote_Selected, 1);
                }
              }
            });
      }

      function f_AddNewMuestra(){
        if ($("#tbl_muestras tr").length == 0){
          return;
        }

        if (!confirm("¿Está seguro de agregar una nueva muestra?")){
          return;
        }

        $.post( "apis/backend.php", { accion: "add_GestionMuestras_NewMuestra", id_grupo: codgrupo_Selected, cod_lote: codlote_Selected },
          function( data ) {
            if(data.estado == 1){
              f_GetListaGrupos(codlote_Selected, 1);
            }
          });
      }

      function f_EliminarMuestra(_id_registro){
        if (!confirm("¿Está seguro de eliminar la muestra seleccionada?")){
          return;
        }

        $.post( "apis/backend.php", { accion: "eliminar_GestionMuestras_Muestra", id_registro: _id_registro },
          function( data ) {
            if(data.estado == 1){
              f_GetListaGrupos(codlote_Selected, 1);
            }
          });
      }
    </script>

    <!-- Funciones de Menús -->
    <script type="text/javascript">
      function f_SetDimension(){
        if (screen.width < 500){
          $("#offcanvasExample").css('width', '60%');

          $("#modal_addcliente_content, #modal_addconductor_content, #modal_addzonaorigen_content, #modal_addacompanante_content").css('margin-top', '10px');
        }
      }

    </script>

    <!-- Funcion Default -->
    <script type="text/javascript">
      
    </script>
  </body>
</html>