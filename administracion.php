<?php 
include("include/error.php"); 
$sesion=explode("|",$_SESSION["usuario"]);

?>
<!DOCTYPE html>
<html lang="es">
<?php
include("include/head.php");
?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php
        include("include/menu.php");
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <?php
                include("include/perfil.php");
                include("include/conn.php");
                
                try { 

                } catch (Exception $e) {
                    echo $e;
                }

                ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                
                    <!-- Page Heading -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Administracion de planes de estudio</h6>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">


<div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" >
          Ciclos
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body" id="cardbodyciclo">

       <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Ciclos</h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addProspectModal" onclick="agregarciclo()">
                            <i class="fas fa-download fa-sm text-white-100"></i>    Agregar ciclo
                        </a>
                    </div>

               
                    <table id="tcont" class="table table-striped table-bordered nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Fecha de inicio</th>
                                            <th>Fecha de termino</th>
                                            <th>Nomenclatura</th>
                                            <th>Tipo de duración</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodycont">
                                      <?php

                                      try { 
                    $stmt = $dbconn->prepare("SELECT c.id,c.fecha_inicio,c.fecha_termino,c.nomenclatura,t.nombre from ciclos c left join tipo_duracion t on t.id=c.tduracion");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    echo $e;
                }
                                         foreach ($rows as $row) {
                                          ?>
                                          <tr>
                                                <td ><p><?=$row["id"] ?></p></td>
                                                <td ><p><?=$row["fecha_inicio"]?></p></td>
                                                <td ><p><?=$row["fecha_termino"]?></p></td>
                                                <td ><p><?=$row["nomenclatura"]?></p></td>
                                                <td ><p><?=$row["nombre"]?></p></td>
                                            </tr>
                                          <?php  } 
                                          ?> </tbody>
                                </table>

      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Carreras
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">

<div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Carreras</h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addProspectModal" onclick="agregarcarrera()">
                            <i class="fas fa-download fa-sm text-white-100"></i>    Agregar carrera
                        </a>
                    </div>

               
                    <table id="tcont2" class="table table-striped table-bordered nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">Id</th>
                                            <th style="width:55%">Carrera</th>
                                            <th style="width:15%">Rvoe</th>
                                            <th style="width:15%"  class="text-center">Ciclos</th>
                                            <th style="width:10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodycont2">
                                      <?php

                                      try { 
                    $stmt = $dbconn->prepare("SELECT * from carreras");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    echo $e;
                }
                                         foreach ($rows as $row) {
                                          ?>
                                          <tr>
                                                <td style="width:5%"><p><?=$row["id"] ?></p></td>
                                                <td style="width:55%"><p><?=$row["nombre"]?></p></td>
                                                <td style="width:15%"><p><?=$row["rvoe"]?></p></td>
                                                <td style="width:15%"><p class="text-center"><?=$row["ciclos"]?></p></td>
                                                <td style="width:10%"><a href="#" data-toggle="modal" data-target="#addProspectModal" onclick="infocarrera(<?=$row['id']?>)"class="btn btn-primary btn-icon-split">
                                                <span class="icon text-white-100">
                                                <i class="fas fa-cogs fa-sm fa-fw"></i>
                                                </span>
                                                <span class="text">Modificar</span> 
                                                </a></td>
                                            </tr>
                                          <?php  } 
                                          ?> </tbody>
                                </table>
      

      </div>
    </div>
  </div>


  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Niveles
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div class="card-body">

        
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Niveles</h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addProspectModal" onclick="agregarNivel()">
                            <i class="fas fa-download fa-sm text-white-100"></i>    Agregar Nivel
                        </a>
                    </div>

               
                    <table id="tcont3" class="table table-striped table-bordered nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">Id</th>
                                            <th style="width:55%">Nivel</th>
                                            <th style="width:20%">Status</th>
                                            <th style="width:20%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodycont3">
                                      <?php

                                      try { 
                                        $stmt = $dbconn->prepare("SELECT * from niveles");
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    } catch (Exception $e) {
                                        echo $e;
                                    }
                                         foreach ($rows as $row) {
                                          ?>
                                          <tr>
                                                <td style="width:5%"><p><?=$row["id"] ?></p></td>
                                                <td style="width:55%"><p><?=$row["nombre"]?></p></td>
                                                <td style="width:20%"><p><?=$row["status"]?></p></td>
                                                <td style="width:20%"><a href="#" data-toggle="modal" data-target="#addProspectModal" onclick="infonivel(<?=$row['id']?>)"class="btn btn-primary btn-icon-split">
                                                <span class="icon text-white-100">
                                                <i class="fas fa-cogs fa-sm fa-fw"></i>
                                                </span>
                                                <span class="text">Modificar</span> 
                                                </a></td>
                                            </tr>
                                          <?php  } 
                                          ?> </tbody>
                                </table>


      </div>
    </div>
  </div>

<div class="card">
    <div class="card-header" id="headingFour">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          Modalidades
        </button>
      </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
      <div class="card-body">


        
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Modalidades</h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addProspectModal" onclick="agregarModalidad()">
                            <i class="fas fa-download fa-sm text-white-100"></i>    Agregar modalidad
                        </a>
                    </div>

               
                    <table id="tcont4" class="table table-striped table-bordered nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">Id</th>
                                            <th style="width:55%">Nombre</th>
                                            <th style="width:20%">Status</th>
                                            <th style="width:20%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodycont4">
                                      <?php

                                      try { 
                                            $stmt = $dbconn->prepare("SELECT * from modalidades");
                                            $stmt->execute();
                                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        } catch (Exception $e) {
                                            echo $e;
                                        }
                                         foreach ($rows as $row) {
                                          ?>
                                          <tr>
                                                <td style="width:5%"><p><?=$row["id"] ?></p></td>
                                                <td style="width:55%"><p><?=$row["nombre"]?></p></td>
                                                <td style="width:20%"><p><?=$row["status"]?></p></td>
                                                <td style="width:20%"><a href="#" data-toggle="modal" data-target="#addProspectModal" onclick="infoModalidad(<?=$row['id']?>)"class="btn btn-primary btn-icon-split">
                                                <span class="icon text-white-100">
                                                <i class="fas fa-cogs fa-sm fa-fw"></i>
                                                </span>
                                                <span class="text">Modificar</span> 
                                                </a></td>
                                            </tr>
                                          <?php  } 
                                          ?> </tbody>
                                </table>

      </div>
    </div>
  </div>


</div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->


            </div>



            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span style="color:#858796">Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php
    include("include/scripts.php");
    ?>
 <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="js/admin.js"></script>
<script> new DataTable('#tcont', {
        responsive: true,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados"
        },
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 filas', '25 filas', '50 filas', 'Todos']
        ],
        layout: {
            topStart: {
                buttons: ['colvis', 'pageLength']
            },
            topEnd: ['search'],
            bottomStart: [{
                buttons: [{
                        extend: 'excel',
                        title: 'Inventario'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'InventarioPDF'
                    },
                    {
                        extend: 'print'
                    }
                ]
            }, 'info'],
            bottomEnd: 'paging'
        }
    });


    $("#tcont_wrapper").removeClass("form-inline");
    $("#tcont_wrapper").addClass("w-100"); 
    
    new DataTable('#tcont2', {
        responsive: true,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados"
        },
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 filas', '25 filas', '50 filas', 'Todos']
        ],
        layout: {
            topStart: {
                buttons: ['colvis', 'pageLength']
            },
            topEnd: ['search'],
            bottomStart: [{
                buttons: [{
                        extend: 'excel',
                        title: 'Inventario'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'InventarioPDF'
                    },
                    {
                        extend: 'print'
                    }
                ]
            }, 'info'],
            bottomEnd: 'paging'
        }
    });


    $("#tcont2_wrapper").removeClass("form-inline");
    $("#tcont2_wrapper").addClass("w-100"); 
    
    
    </script>



</body> 

</html>