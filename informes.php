<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<?php
include("include/head.php");
?>
<style>
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
        color: #858796; /* Color de texto */
    }

    .scrollable {
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        margin-top: 20px;
        color: #858796; /* Color de texto */
    }

    .scrollable .row {
        flex-wrap: wrap;
        color: #858796; /* Color de texto */
    }

    .scrollable .col-md-2 {
        margin-bottom: 10px;
        color: #858796; /* Color de texto */
    }

    .image-container {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .modal-body hr {
        margin: 20px 0;
        border-color: #858796; /* Cambiar color de la línea horizontal si es necesario */
    }
    .modal-title{
        color: #858796; /* Color de texto */
    }
    .footer{
        color: #858796; /* Color de texto */

    }

    .centrar{
    display: flex;
    justify-content: center;
    }
</style>



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
                $usuario="ControlEscolar";

                $tabla="";
                if($usuario=="Promocion"){
                    $tabla="Prospectos";
                    $tipo=0; //saber los tipos de status para los prospectos e inscritos
                }else{
                    $tabla="Inscritos";
                    $tipo=100;//saber los tipos de status que hay para los alumnos activos
                }

                try {
                    $stmt = $dbconn->prepare("SELECT * FROM following WHERE status =".$tipo." limit 50");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    echo $e;
                }

                ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?=$tabla?></h1>
                    </div>

                    <!-- Page Heading -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tabla de contenido</h6>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">

                                <table id="tcont" class="table table-striped table-bordered nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php foreach ($rows as $row) { ?>
                                            <tr>
                                                <td><?= $row["fid"] ?></td>
                                                <td><?= $row["name"] ?></td>
                                                <td><?= $row["surname1"] ?></td>
                                                <td><?= $row["surname2"] ?></td>
                                                <td><?= $row["pltid"] ?></td>
                                                <td style="text-align: center;">
                                                <a href="#"  onclick="prospectoDetalles(<?=$row['fid']?>)" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-icon-split">
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
            <!-- End of Main Content -->
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" data-keyboard="false" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" id="modalProspectoBody">
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addProspectModal" tabindex="-1" role="dialog" aria-labelledby="addProspectModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Agregar prospecto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <!-- Formulario de Prospecto -->
                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                        <!-- Primera Fila: Nombre, Apellido Paterno, Apellido Materno -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" required> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellidoPaterno">Apellido Paterno</label>
                                <input type="text" class="form-control" id="apellidoPaterno" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellidoMaterno">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellidoMaterno" required>
                            </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Segunda Fila: Nivel (select), Modalidad (select), Ciclo -->
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="nivel">Nivel</label>
                                <select class="form-control" id="nivel" required>
                                    <option value="Prepa">Prepa</option>
                                    <option value="Uni">Uni</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalidad">Modalidad</label>
                                <select class="form-control" id="modalidad" required>
                                    <option value="Escolar">Escolar</option>
                                    <option value="Sabatino">Sabatino</option>
                                </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ciclo">Ciclo</label>
                                            <input type="text" class="form-control" id="ciclo" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Tercera Fila: Teléfono, Correo Electrónico -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono</label>
                                            <input type="number" class="form-control" id="telefono" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Cuarta Fila: Comentarios -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="comentarios">Comentarios</label>
                                            <textarea class="form-control" id="comentarios" rows="3" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="saveProspectBtn" onclick="saveProspect()">Guardar Prospecto</button>
                        </div>

                    </div>
                </div>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>



    <?php
    include("include/scripts.php");
    ?>

    <script src="js/promocion.js"></script>
    
    <script>
        new DataTable('#tcont', {
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
        $("#tcont_wrapper").addClass("w-100");
    </script>

</body>

</html>