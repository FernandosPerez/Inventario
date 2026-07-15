<?php
include("include/error.php");
?>
<!DOCTYPE html>
<html lang="es">

<?php
include("include/head.php");
?>

<!-- Agregar FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.js"></script>



<!-- Estilos personalizados -->
<style>
   
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
                ?>

<div class="container mt-4">
                    <!-- Título y botón en la misma fila -->
                    <div class="row align-items-center">
                        <div class="col">
                            <p style="color:#858796; text-align: start; font-size: 40px;">Calendario de Eventos</p>
                        </div>
                        <div class="col text-right">
                            <button id="addEventButton" class="btn btn-primary mb-3" data-toggle="modal" data-target="#eventModal">
                                <i class="fas fa-plus"></i> Agregar Evento
                            </button>
                        </div>

                    </div>

                    <!-- Contenedor del Calendario -->
                    <div id="calendar" style="color:#858796; text-align: start; font-size: 15px;"></div>
                </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">|
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

    <!-- Modal para agregar eventos -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Agregar Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <div class="form-group">
                            <label for="eventTitle">Título del Evento</label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                        <div class="form-group">
                            <label for="eventDate">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="eventDate" required>
                        </div>
                        <div class="form-group">
                            <label for="eventEndDate">Fecha de Fin</label>
                            <input type="date" class="form-control" id="eventEndDate" required>
                        </div>
                        <div class="form-group">
                            <label for="eventDetails">Detalles del Evento</label>
                            <textarea class="form-control" id="eventDetails" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Evento</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    

</body>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Estás listo para salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecciona "Salir" si estás listo para terminar tu sesión actual.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="login.html">Salir</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts necesarios -->
    <?php
    include("include/scripts.php");
    ?>

<script src="js/calendario.js"></script>

</html>
