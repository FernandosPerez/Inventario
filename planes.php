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
                    
                    $stmt = $dbconn->prepare("SELECT COUNT(pc.plan) AS conceptos,p.id as id,p.nombre AS nombre,p.fecha_limite AS dia FROM plan_pagos p LEFT JOIN plan_pagos_conceptos pc ON pc.plan=p.id WHERE p.plantel='".$_GET['plantel']."' GROUP BY pc.plan");
                    $stmt->execute();
                    $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $stmt = $dbconn->prepare("SELECT * from conceptos where plantel='".$_GET['plantel']."' order by nombre asc");
                    $stmt->execute();
                    $conceptos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"></h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="regresar()">
                            <i class="fa-solid fa-hand-point-left text-white-100"></i>  Regresar
                        </a>
                    </div>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Planes</h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addProspectModal" onclick="agregarPlan(<?=$_GET['plantel']?>)">
                            <i class="fas fa-download fa-sm text-white-100"></i>   Agregar plan
                        </a>
                    </div>

                    <!-- Page Heading -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Administración de planes</h6>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">

                                <table id="tcont" class="table table-striped table-bordered nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th >Id</th>
                                            <th >Nombre</th>
                                            <th >Conceptos</th>
                                            <th >Dia de pago</th>
                                            <th >Administrar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($planes as $row) { ?>
                                            <tr>
                                                <td ><?= $row["id"]?></td>
                                                <td ><?= $row["nombre"]?></td>
                                                <td ><?= $row["conceptos"] ?></td>
                                                <td ><?= $row["dia"] ?></td>
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

                    <!-- tabla de conceptos -->     
                     
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Planes</h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addProspectModal" onclick="agregarConcepto(<?=$_GET['plantel']?>)">
                            <i class="fas fa-download fa-sm text-white-100"></i>   Agregar Concepto
                        </a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h5 class="m-0 font-weight-bold text-primary">Administración de conceptos</h5>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">

                                <table id="tcont2" class="table table-striped table-bordered nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th>Descuento</th>
                                            <th>Monto</th>
                                            <th>Concurrencia</th>
                                            <th>status</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($conceptos as $row) { ?>
                                            <tr>
                                                <td ><?= $row["id"]?></td>
                                                <td ><?= $row["nombre"]?></td>
                                                <td ><?= $row["descuento"] ?></td>
                                                <td ><?= $row["monto"] ?></td>
                                                <td ><?= $row["concurrencia"] ?></td>
                                                <td ><?= $row["status"] ?></td>
                                                <td style="text-align: center;">
                                                <a onclick="plantel(<?=$row['id']?>)" class="btn btn-primary btn-icon-split">
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
                    <!-- /.container-fluid -->


                    <?php

                }else{
                    $stmt = $dbconn->prepare("SELECT * FROM planteles");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                    ?>
<!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Planes</h1>
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
                                            <th>Nombre</th>
                                            <th>Total de planes de pago</th>
                                            <th>Administrar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $row) { ?>
                                            <tr>
                                                <td><?= $row["nombre"]?></td>
                                                <td><?= $row["ubicacion"] ?></td>
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

<?php } ?>
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
                        title: 'PlanesDePago'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'PlanesDePagoPDF'
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
                        title: 'Conceptos'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'ConceptosPDF'
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