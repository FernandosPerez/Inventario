<?php
include("include/error.php");
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
                
                if($_GET["plantel"]!=0){ 
                    
                    try {
                    $stmt = $dbconn->prepare("SELECT * FROM planteles where id='".$_GET["plantel"]."'");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $stmt = $dbconn->prepare("SELECT COUNT(pc.plan) AS conceptos,p.id as id,p.nombre AS nombre,p.fecha_limite AS dia FROM plan_pagos p LEFT JOIN plan_pagos_conceptos pc ON pc.plan=p.id WHERE p.plantel='".$_GET['plantel']."' GROUP BY pc.plan");
                    $stmt->execute();
                    $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if($rows[0]["status"]==1){
                        $status="checked";
                    }
                } catch (Exception $e) {
                    echo $e;
                }
                    ?>


                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Planteles</h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="regresar()">
                            <i class="fa-solid fa-hand-point-left text-white-100"></i>  Regresar
                        </a>
                    </div>

                    <!-- Page Heading -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row">
                            <div class="col-6"><h6 class="m-0 font-weight-bold text-primary"><?=$rows[0]["nombre"]?></h6></div>
                            
                            <div class="col-6" style="text-align:right">  

                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="status" <?=$status?>>
                            <label class="custom-control-label" for="status">Status</label>
                            </div>
                            </div>

                           
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-6">
                                         <div class="row">
                                            <div class="form-group">
                                            <label for="nombre">NOMBRE</label>
                                            <input type="text" class="form-control" id="nombre" required value="<?=$rows[0]["nombre"]?>">
                                        </div>
                                        </div> 
                                    </div> 

                                    <div class="col-6">
                                        <div class="row">
                                            <div class="form-group">
                                            <label for="ubicacion">UBICACIÓN</label>
                                            <input type="text" class="form-control" id="ubicacion" required value="<?=$rows[0]["ubicacion"]?>">
                                        </div>
                                        </div> 
                                    </div> 
                                </div> 

                                <div class="row">
                                    <div class="col-6">
                                         <div class="row">
                                            <div class="form-group">
                                            <label for="razon">RAZÓN SOCIAL</label>
                                            <input type="text" class="form-control" id="razon" required value="<?=$rows[0]["razon_social"]?>">
                                        </div>
                                        </div> 
                                    </div> 

                                    <div class="col-6">
                                        <div class="row">
                                            <div class="form-group">
                                            <label for="nomen">NOMENCLATURA</label>
                                            <input type="text" class="form-control" id="nomen" required value="<?=$rows[0]["nomenclatura"]?>">
                                        </div>
                                        </div> 
                                    </div> 
                                </div> 

                                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                    <p>Llena todos los campos</p>
                                    <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="actualizarPlantel(<?=$rows[0]['id']?>)">
                                        <i class="fa-solid fa-floppy-disk text-white-100"></i>  Guardar Cambios
                                    </a>
                                </div>
                                
                            </div>
                        </div>
                    </div>


                    <!----- administracion del plantel----->
                    <div class="row">

                    <div class="col-md-8">
                         <div class="card shadow m-1">
                        <div class="card-header py-3">
                            <div class="row">
                            <div class="col-6"><h6 class="m-0 font-weight-bold text-primary">Planes de pago</h6></div>
                            
                            <div class="col-6" style="text-align:right"><h6 class="m-0 font-weight-bold text-primary">16</h6></div>

                           
                            </div>
                        </div>
                        <div class="card-body">
                             <div class="container-fluid">

                                <table id="tcont" class="table table-striped table-bordered nowrap">
                                    <thead>l
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th>conceptos</th>
                                            <th>Fecha límite</th>
                                            <th>status</th>
                                            <th>⚙️</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($planes as $row) { ?>
                                            <tr>
                                                <td><?= $row["id"] ?></td>
                                                <td><?= $row["nombre"]?></td>
                                                <td><?= $row["conceptos"] ?></td>
                                                <td><?= $row["fecha_limite"] ?></td>
                                                <td><?= $row["status"] ?></td>
                                                <td style="text-align: center;">
                                                <a onclick="planesPlantel(<?=$row['id']?>)" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#addProspectModal">
                                                <span class="icon text-white-100">
                                                    <i class="fas fa-cogs fa-sm fa-fw"></i>
                                                </span>
                                                <span class="text">Modificar</span>
                                                </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>

                    
                <div class="col-md-4">

                 <div class="card shadow m-1">
                        <div class="card-header py-3">
                            <div class="row">
                            <div class="col-6"><h6 class="m-0 font-weight-bold text-primary"><?=$rows[0]["nombre"]?></h6></div>
                            
                            <div class="col-6" style="text-align:right">  

                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="status" <?=$status?>>
                            <label class="custom-control-label" for="status">Status</label>
                            </div>

                            </div>

                           
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-6">
                                         <div class="row">
                                            <div class="form-group">
                                            <label for="nombre">NOMBRE</label>
                                            <input type="text" class="form-control" id="nombre" required value="<?=$rows[0]["nombre"]?>">
                                        </div>
                                        </div> 
                                    </div> 

                                    <div class="col-6">
                                        <div class="row">
                                            <div class="form-group">
                                            <label for="ubicacion">UBICACIÓN</label>
                                            <input type="text" class="form-control" id="ubicacion" required value="<?=$rows[0]["ubicacion"]?>">
                                        </div>
                                        </div> 
                                    </div> 
                                </div> 

                                <div class="row">
                                    <div class="col-6">
                                         <div class="row">
                                            <div class="form-group">
                                            <label for="razon">RAZÓN SOCIAL</label>
                                            <input type="text" class="form-control" id="razon" required value="<?=$rows[0]["razon_social"]?>">
                                        </div>
                                        </div> 
                                    </div> 

                                    <div class="col-6">
                                        <div class="row">
                                            <div class="form-group">
                                            <label for="nomen">NOMENCLATURA</label>
                                            <input type="text" class="form-control" id="nomen" required value="<?=$rows[0]["nomenclatura"]?>">
                                        </div>
                                        </div> 
                                    </div> 
                                </div> 

                                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                    <p>Llena todos los campos</p>
                                    <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="actualizarPlantel(<?=$rows[0]['id']?>)">
                                        <i class="fa-solid fa-floppy-disk text-white-100"></i>  Guardar Cambios
                                    </a>
                                </div>
                                
                            </div>
                        </div>
                    </div>
</div>

                    </div>

                </div>
                <!-- /.container-fluid -->

<?php            


}else{

                try {
                    $stmt = $dbconn->prepare("SELECT * FROM planteles");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    echo $e;
                }
                    ?>
<!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Planteles</h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addProspectModal" onclick="agregarPlantel()">
                            <i class="fas fa-download fa-sm text-white-100"></i>   Agregar plantel
                        </a>
                    </div>

                    <!-- Page Heading -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Administración de planteles</h6>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">

                                <table id="tcont" class="table table-striped table-bordered nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th>Ubicación</th>
                                            <th>Razón Social</th>
                                            <th>Nomenclatura</th>
                                            <th>Status</th>
                                            <th>Funciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $row) { ?>
                                            <tr>
                                                <td><?= $row["id"] ?></td>
                                                <td><?= $row["nombre"]?></td>
                                                <td><?= $row["ubicacion"] ?></td>
                                                <td><?= $row["razon_social"] ?></td>
                                                <td><?= $row["nomenclatura"] ?></td>
                                                <td><?= $row["status"] ?></td>
                                                <td style="text-align: center;">
                                                <a onclick="plantel(<?=$row['id']?>)" class="btn btn-primary btn-icon-split">
                                                <span class="icon text-white-100">
                                                    <i class="fas fa-cogs fa-sm fa-fw"></i>
                                                </span>
                                                <span class="text">Detalles</span>
                                                </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

<?php
                }
?>
                


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
                        title: 'ProspectosExcel'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'ProspectosPDF'
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
    $("#tcont_wrapper").addClass("w-100");</script>
</body> 

</html>