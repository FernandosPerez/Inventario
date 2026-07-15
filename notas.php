<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
                    $stmt = $dbconn->prepare("SELECT * FROM following WHERE status =0 ");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    echo $e;
                }

                ?>

  <!-- Contenedor de notas -->
  <div id="notes-container" class="container">
                    <h1>Notas con recordatorios importantes</h1>
                    <div id="note-list"></div>
                    <button id="add-note" class="btn btn-primary">Agregar Nota</button>
                </div>

                <!-- Modal para crear o editar una nota -->
                <div id="noteModal" class="modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nota</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <textarea id="note-content" class="form-control" placeholder="Escribe tu nota aquí..."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" id="save-note">Guardar Nota</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2020</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->


        <?php
        include("include/scripts.php");
        ?>

        <script src="js/notas.js"></script>

</body>

</html>