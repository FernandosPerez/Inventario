<?php 
include("include/error.php");
$sesion=explode("|",$_SESSION["usuario"]);

$contenido= "Prospectos";

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
                    $stmt = $dbconn->prepare("SELECT * FROM usuarios where rol=17 ");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    echo $e;
                }

                ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?=$contenido?></h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addProspectModal" onclick="agregar<?=$contenido?>()">
                            <i class="fa-solid fa-user-plus text-white-100 pr-2"></i>Agregar carrera
                        </a>
                    </div>
                    <!-- Page Heading -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tabla de <?=$contenido?></h6>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">

                                <table id="tcont" class="table table-striped table-bordered nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th>Plantel</th>
                                            <th>Status</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $row) { ?>
                                            <tr>
                                                <td><?= $row["id"] ?></td>
                                                <td><?= $row["nombre"]." ".$row["apellidoP"]." ".$row["apellidoM"] ?></td>
                                                <td><?= $row["campus"] ?></td>
                                                <td><?= $row["status"] ?></td>
                                                <td style="text-align: center;">
                                                <a href="#"  onclick="datos(<?=$row['id']?>)" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-icon-split">
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

    <script src="js/promocion.js"></script>
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