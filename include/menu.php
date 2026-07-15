<?php include('conn.php'); $perfil=explode("|",$_SESSION["usuario"]);?>

<div class="sidebar  bg-primary" id="sidebar">
    <!-- Sidebar -->
    <ul class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-icon">
        <img src="img\secuiep-white[1].png" alt="SECUIEP Logo" style="width: 40px; height: auto;">
    </div>
            <div class="sidebar-brand-text mx-3">SECUIEP</div>
        </a>
        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading" style="color:white">Modulos</div>
        
        <?php include('roles.php'); ?>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading" style="color:white">Adicionales</div>

        <?php

         $qry = $dbconn->prepare("SELECT * FROM permisos WHERE usuario='".$perfil[0]."'");
        $qry->execute();
        $rowals = $qry->fetchAll(PDO::FETCH_ASSOC);

        if($rowals[0]["chat"]==1){
?>
<!-- Nav Item - Chats -->
        <li class="nav-item">
            <a class="nav-link" href="chats.php">
            <i class="fas fa-fw fa-comments" style="color: #e8e8e8;"></i>
                <span>Chats</span></a>
        </li>

<?php
        }if($rowals[0]["asist"]==1){
?>
 <!-- Nav Item - Utilities Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fa-solid fa-qrcode" style="color: #e8e8e8;"></i>
                <span>Asistencia</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Herramientas:</h6>
                                <a class="collapse-item" href="asistencia.php" style="color:gray">Asistencia</a>
                    <a class="collapse-item" href="registros.php" style="color:gray">Mis registros</a>
                </div>
            </div>
        </li>

<?php
        }if($rowals[0]["noticias"]==1){
?>


<?php
        }if($rowals[0]["calendario"]==1){
?>

<!-- Nav Item - Chats -->
        <li class="nav-item">
            <a class="nav-link" href="calendario.php">
            <i class="fas fa-fw fa-calendar" style="color: #e8e8e8;"></i>
                <span>Calendario</span></a>
        </li>
<?php
        }if($rowals[0]["alertas"]==1){
?>


<?php
        }if($rowals[0]["blog"]==1){
?>
<!-- Nav Item - Chats -->
        <li class="nav-item">
            <a class="nav-link" href="blog.php">
            <i class="fas fa-fw fa-blog" style="color: #e8e8e8;"></i>
                <span>Blog</span></a>
        </li>

<?php
        }if($rowals[0]["reportes"]==1){
?>
<!-- Nav Item - notes -->
        <li class="nav-item">
            <a class="nav-link" href="reporte_sistemas.php">
            <i class="fas fa-file-alt" style="color: #e8e8e8;"></i>
                <span>Reporte de sistemas</span></a>
        </li>

<?php
        }if($rowals[0]["tienda"]==1){
?>
 <!-- Nav Item - notes -->
        <li class="nav-item">
            <a class="nav-link" href="tienda.php">
            <i class="fas fa-store" style="color: #e8e8e8;"></i>
                <span>Tienda Online</span></a>
        </li>

<?php
        }if($rowals[0]["notas"]==1){
?>
<!-- Nav Item - notes -->
        <li class="nav-item">
            <a class="nav-link" href="notas.php">
            <i class="fas fa-fw fa-sticky-note" style="color: #e8e8e8;"></i>
                <span>Notas</span></a>
        </li>

<?php
        }if($rowals[0]["evaluaciones"]==1){
?>
<!-- Nav Item - notes -->
        <li class="nav-item">
            <a class="nav-link" href="evaluaciones.php">
            <i class="fas fa-fw fa-sticky-note" style="color: #e8e8e8;"></i>
                <span>Evaluaciones</span></a>
        </li>

<?php
        }if($rowals[0]["capacitaciones"]==1){
?>
<!-- Nav Item - notes -->
        <li class="nav-item">
            <a class="nav-link" href="capacitaciones.php">
            <i class="fas fa-fw fa-sticky-note" style="color: #e8e8e8;"></i>
                <span>Capacitaciones</span></a>
        </li>

<?php
        }if($rowals[0]["ia"]==1){
?>
<!-- Nav Item - notes -->
        <li class="nav-item">
            <a class="nav-link" href="ia.php">
            <i class="fas fa-fw fa-sticky-note" style="color: #e8e8e8;"></i>
                <span>I.A.</span></a>
        </li>

<?php
        }if($rowals[0]["kpi"]==1){
?>
<!-- Nav Item - notes -->
        <li class="nav-item">
            <a class="nav-link" href="kpi.php">
            <i class="fas fa-fw fa-sticky-note" style="color: #e8e8e8;"></i>
                <span>KPI´S</span></a>
        </li>

<?php
        }if($rowals[0]["reuniones"]==1){
?>
<!-- Nav Item - notes -->
        <li class="nav-item">
            <a class="nav-link" href="reuniones.php">
            <i class="fas fa-fw fa-sticky-note" style="color: #e8e8e8;"></i>
                <span>Reuniones</span></a>
        </li>

<?php
        }if($rowals[0]["otros"]==1){
?>
<!-- Nav Item - notes -->
        <li class="nav-item">
            <a class="nav-link" href="otros.php">
            <i class="fas fa-fw fa-sticky-note" style="color: #e8e8e8;"></i>
                <span>Otros</span></a>
        </li>

<?php
        }
        
        ?>
        
        
        
        
       
        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
</div>

<!-- JavaScript 
<script>
    function changeSidebarColor(colorClass) {
        const sidebars = [
            document.getElementById('sidebar'),
            document.getElementById('accordionSidebar')
        ];

        sidebars.forEach(function(sidebar) {
            sidebar.classList.remove('bg-purple', 'bg-red', 'bg-blue', 'bg-green');
            sidebar.classList.add(colorClass);
        });

        document.querySelectorAll('.nav-link, .sidebar-brand-text, .fas').forEach(element => {
            element.style.color = "#ffffff"; 
        });

        document.querySelectorAll('.collapse-item').forEach(item => {
            item.style.color = "#ffffff";
        });

        console.log('Color del Sidebar: ' + colorClass);
        localStorage.setItem('sidebarColor', colorClass); 
    }
    window.onload = function() {
        const savedSidebarColor = localStorage.getItem('sidebarColor');
        if (savedSidebarColor) {
            changeSidebarColor(savedSidebarColor); 
        }
    };
</script>
-->