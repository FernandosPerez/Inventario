<?php
include("include/error.php");
session_start();
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
               

                ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">
                    
                <?php

                 
                try {
                    $stmt = $dbconn->prepare("SELECT * from log where usuario='".$sesion[0]."'");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    echo $e;
                }
                foreach ($rows as $row) {

                    ?>
                            <p>Fecha de registro : <?=$row["hora"]?></p>
                            <p><?=$row["descripcion"]?></p> 
                            <hr class="sidebar-divider">
                     <br>
                    <?php

                }

                ?>

                </div>
                <!-- /.container-fluid -->


            </div>
            <!-- End of Main Content -->



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
</body> 

</html>