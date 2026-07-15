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
                    $stmt = $dbconn->prepare("SELECT * FROM usuarios where id='".$sesion[0]."' ");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                            <h6 class="m-0 font-weight-bold text-primary">Información acerca de mi perfil </h6>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">

                            <!-- <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 25%" id="progress"></div>
                            </div> -->

                            <div class="row">
                                <button class="btn btn-primary" onclick="progreso()">Avanzar progreso</button>
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
    <script src="js/admin.js"></script>

</body> 

</html>