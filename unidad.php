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
                ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Calificaciones</h1>
                        <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addProspectModal" onclick="agregar()">
                            <i class="fas fa-download fa-sm text-white-50"></i> Agregar prospecto
                        </a>
                    </div>

                    <!-- Page Heading -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">
                                <?php
                                session_start();
                                if(!isset($_SESSION['eco']))
                                { ?>
                            <button onclick="unidad(2)" class="btn btn-primary">Registrar unidad</button>
                            <?php }else{
                                ?>
                            <button onclick="unidad(3)" class="btn btn-primary">Cambiar unidad</button>    
                            <?php } ?>
                                <div id="reader"></div>
                                <br>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->


            </div>
            <!-- End of Main Content -->
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" data-keyboard="false" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" id="datosModal">
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addProspectModal" tabindex="-1" role="dialog" aria-labelledby="addProspectModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" id="agregarModal">

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


    <?php
    include("include/scripts.php");
    ?>

    <script src="js/promocion.js"></script>
    <script src="js/html5-qrcode.min.js"></script>
    <script src="js/chequeos.js"></script>
</body> 

</html>