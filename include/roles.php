<?php include('conn.php'); 

$sesion=explode("|",$_SESSION["usuario"]);

if($sesion[1]==1){
    //ADMINISTRADOR
?>
    <!-- Nav Item - Utilities Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Administrador"
                aria-expanded="true" aria-controls="Administrador">
                <i class="fas fa-star" style="color:rgb(255, 153, 0);"></i>
                <span>Administrador</span>
            </a>
            <div id="Administrador" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Herramientas:</h6>
                    <a class="collapse-item" href="admin.php" style="color:#85888e">Inicio</a>
                    <a class="collapse-item" href="planteles.php" style="color:#85888e">Planteles</a>
                    <a class="collapse-item" href="planes.php" style="color:#85888e">Planes / Conceptos</a>
                    <a class="collapse-item" href="usuarios.php" style="color:#85888e">Usuarios</a>
                    <a class="collapse-item" href="administracion.php" style="color:#85888e">Planes de estudio</a>
                </div>
            </div>
        </li>
        <!-- Nav Item - Utilities Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Inventario"
                aria-expanded="true" aria-controls="Inventario">
                <i class="fa-solid fa-boxes-stacked" style="color: #e8e8e8;"></i>
                <span>Inventario</span>
            </a>
            <div id="Inventario" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Herramientas:</h6>
                    <a class="collapse-item" href="inventario.php" style="color:#85888e">Inventario</a>
                    <a class="collapse-item" href="inventario_reportes.php" style="color:#85888e">Reportes</a>
                    <!--<a class="collapse-item" href="proveedores.php" style="color:#85888e">Proveedores</a>-->
                    <!--<a class="collapse-item" href="historial_compras.php" style="color:#85888e">Historial de compras</a>-->
                    <!--<a class="collapse-item" href="evalucion.php" style="color:#85888e">Evaluación</a>-->
                </div>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#ControlEscolar"
                aria-expanded="true" aria-controls="ControlEscolar">
                <i class="fas fa-book" style="color: #e8e8e8;"></i>
                <span>Control Escolar</span>
            </a>
            <div id="ControlEscolar" class="collapse" aria-labelledby="ControlEscolar" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Herramientas</h6>
                    <a class="collapse-item" href="dal.php?type=2" style="color:#85888e">Alumnos</a>
                    <a class="collapse-item" href="calificaciones.php" style="color:#85888e">Calificaciones</a>
                </div>
            </div>
        </li>

        <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Promocion"
            aria-expanded="true" aria-controls="Promocion">
            <i class="fas fa-fw fa-bullhorn" style="color: #e8e8e8;"></i>
            <span>Promoción</span>
        </a>
        <div id="Promocion" class="collapse" aria-labelledby="Promocion" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Administración</h6>
                <a class="collapse-item" href="promocionAdministracion.php" style="color:#85888e">Administración</a>
                <hr class="sidebar-divider">
                <h6 class="collapse-header">Herramientas</h6>
                <a class="collapse-item" href="difusion.php" style="color:#85888e">Difusión</a>
                <a class="collapse-item" href="informes.php" style="color:#85888e">Informes</a>
                <a class="collapse-item" href="prospectos.php" style="color:#85888e">Prospectos</a>
                <a class="collapse-item" href="referencias.php" style="color:#85888e">Referencias</a>
            </div>
        </div>
    </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Finanzas"
                aria-expanded="true" aria-controls="Finanzas">
                <i class="fas fa-money-bill-alt" style="color: #e8e8e8;"></i>
                <span>Finanzas</span>
            </a>
            <div id="Finanzas" class="collapse" aria-labelledby="Finanzas" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Herramientas</h6>
                    <a class="collapse-item" href="dal.php?type=2" style="color:#85888e">Alumnos</a>
                    <a class="collapse-item" href="calificaciones.php" style="color:#85888e">Calificaciones</a>
                </div>
            </div>
        </li>
        
<?php
}else if($sesion[1]==2){
    //PROMOTOR
?>    <!-- Nav Item - Pages Collapse Menu -->

        <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Promocion"
            aria-expanded="true" aria-controls="Promocion">
            <i class="fas fa-fw fa-bullhorn" style="color: #e8e8e8;"></i>
            <span>Promoción</span>
        </a>
        <div id="Promocion" class="collapse" aria-labelledby="Promocion" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Herramientas</h6>
                <a class="collapse-item" href="difusion.php" style="color:#85888e">Difusión</a>
                <a class="collapse-item" href="informes.php" style="color:#85888e">Informes</a>
                <a class="collapse-item" href="dal.php?type=1" style="color:#85888e">Prospectos</a>
                <a class="collapse-item" href="referencias.php" style="color:#85888e">Referencias</a>
            </div>
        </div>
    </li>
<?php
}else if($sesion[1]==3){
    //DIRECTOR ADMINISTRATIVO
    ?>
    <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Inventario"
                aria-expanded="true" aria-controls="Inventario">
                <i class="fa-solid fa-boxes-stacked" style="color: #e8e8e8;"></i>
                <span>Inventario</span>
            </a>
            <div id="Inventario" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Herramientas:</h6>
                    <a class="collapse-item" href="inventario.php" style="color:#85888e">Inventario</a>
                    <a class="collapse-item" href="inventario_reportes.php" style="color:#85888e">Reportes</a>
                    <!--<a class="collapse-item" href="proveedores.php" style="color:#85888e">Proveedores</a>-->
                    <!--<a class="collapse-item" href="historial_compras.php" style="color:#85888e">Historial de compras</a>-->
                    <!--<a class="collapse-item" href="evalucion.php" style="color:#85888e">Evaluación</a>-->
                </div>
            </div>
        </li>
        <?php
}else if($sesion[1]==4){
    //SERVICIOS GENERALES

    ?>
    <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Inventario"
                aria-expanded="true" aria-controls="Inventario">
                <i class="fa-solid fa-boxes-stacked" style="color: #e8e8e8;"></i>
                <span>Inventario</span>
            </a>
            <div id="Inventario" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Herramientas:</h6>
                    <a class="collapse-item" href="inventario.php" style="color:#85888e">Inventario</a>
                    <a class="collapse-item" href="inventario_reportes.php" style="color:#85888e">Reportes</a>
                    <!--<a class="collapse-item" href="proveedores.php" style="color:#85888e">Proveedores</a>-->
                    <!--<a class="collapse-item" href="historial.php" style="color:#85888e">Historial de inventario</a>-->
                    <!--<a class="collapse-item" href="evaluacion.php" style="color:#85888e">Evaluación</a>-->
                </div>
            </div>
        </li>
        <?php
}else if($sesion[1]==5){
    //DIRECTOR FINANZAS
?>

<?php
}else if($sesion[1]==6){
    //COORDINADOR FINANZAS
?>

<?php    
}else if($sesion[1]==7){
    //ACADEMIA
}else if($sesion[1]==8){
    //COORDINADOR DE CONTROL ESCOLAR
}else if($sesion[1]==9){
    //COORDINADOR ACADÉMICO
}else if($sesion[1]==10){
    //ALUMNO
}else if($sesion[1]==11){
    //DOCENTE
}else if($sesion[1]==12){
    //RECURSOS HUMANOS
}else if($sesion[1]==13){
    //DIRECTOR GENERAL (DE CAMPUS)
}else if($sesion[1]==14){
    //RECOTOR
}else if($sesion[1]==15){
    //DIRECTOR DE IMAGEN
}else if($sesion[1]==16){
    //SOPORTE IT
}else if($sesion[1]==17){
    //PROSPECTO
}else if($sesion[1]==18){
    //COORDINADOR ADMINISTRATIVO
?>
    <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Inventario"
                aria-expanded="true" aria-controls="Inventario">
                <i class="fa-solid fa-boxes-stacked" style="color: #e8e8e8;"></i>
                <span>Inventario</span>
            </a>
            <div id="Inventario" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Herramientas:</h6>
                    <a class="collapse-item" href="inventario.php" style="color:#85888e">Inventario</a>
                    <a class="collapse-item" href="inventario_reportes.php" style="color:#85888e">Reportes</a>
                    <!-- <a class="collapse-item" href="proveedores.php" style="color:#85888e">Proveedores</a>
                    <a class="collapse-item" href="historial_compras.php" style="color:#85888e">Historial de compras</a>
                    <a class="collapse-item" href="evalucion.php" style="color:#85888e">Evaluación</a> -->
                </div>
            </div>
        </li>
        <?php
}
?>