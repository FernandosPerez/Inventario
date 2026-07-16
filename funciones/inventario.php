<?php
// error_reporting(E_ALL & ~E_NOTICE);
// ini_set('display_errors', 1);

include("../include/conn.php");
session_start();
$sesion=explode("|",$_SESSION["usuario"]);

switch($_REQUEST['op']){
	case 1:

        $stmt = $dbconn->prepare("SELECT * FROM usuarios WHERE id='".$_POST['id']."'");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $dbconn->prepare("SELECT * FROM planteles");
        $stmt->execute();
        $planteles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $dbconn->prepare("SELECT * FROM roles");
        $stmt->execute();
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $dbconn->prepare("SELECT * FROM permisos where usuario='".$_POST['id']."'");
        $stmt->execute();
        $permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response=' <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Datos del usuario: '.$rows[0]["nombre"].' '.$rows[0]["apellidoP"] .' '.$rows[0]["apellidoM"] .'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Parte superior -->
                                        <div class="row">
                                        <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" required value="'.$rows[0]["nombre"].'"> 
                                        </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="apellidoPaterno">Apellido Paterno</label>
                                                <input type="text" class="form-control" id="apellidoPaterno" required value="'.$rows[0]["apellidoP"].'">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="apellidoMaterno">Apellido Materno</label>
                                                <input type="text" class="form-control" id="apellidoMaterno" required value="'.$rows[0]["apellidoM"].'">
                                            </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="usuario">Usuario</label>
                                            <input type="text" class="form-control" id="usuario" required value="'.$rows[0]["cuenta"].'"> 
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="correo">Correo</label>
                                                <input type="text" class="form-control" id="correo" required value="'.$rows[0]["correo"].'">
                                            </div>
                                        </div>
                                        </div>


                                        <div class="row">
                                        <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="telefono">Telefono</label>
                                            <input type="number" class="form-control" id="telefono" required value="'.$rows[0]["telefono"].'" max="10"> 
                                        </div>
                                        </div>
                                        </div>

                    <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="1">C.P.</label>
                            <div class="input-group ">
                                <input type="text" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="5" id="1" autocomplete="off" value="'.$rows[0]["cp"].'">
                                <span class="input-group-text" id="basic-addon2" style="background-color: #2e59d9;" onclick="codigo()"><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="2">Calle</label>
                            <input type="text" class="form-control" id="2" autocomplete="off" value="'.$rows[0]["calle"].'">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="6">Numero ext.</label>
                            <input type="text" class="form-control" id="6" autocomplete="off" data-toggle="tooltip" data-placement="top" title="Si no cuenta con numero, ingresa 0" value="'.$rows[0]["numeroext"].'">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="7">Numero Int.</label>
                            <input type="text" class="form-control" id="7" autocomplete="off" data-toggle="tooltip" data-placement="top" title="Si no cuenta con numero, ingresa 0" value="'.$rows[0]["numeroint"].'">
                        </div>
                    </div>
                </div>


                 <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="3">Localidad</label>
                            <select class="form-control" id="3">
                            <option value="'.$rows[0]["localidad"].'">'.$rows[0]["localidad"].'</option>
                            </select>
                        </div>
                    </div>
                    </div>
                 <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="4">Municipio</label>
                            <input type="text" class="form-control" id="4" disabled value="'.$rows[0]["municipio"].'">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="5">Estado</label>
                            <input type="text" class="form-control" id="5" disabled value="'.$rows[0]["estado"].'">
                        </div>
                    </div>
                </div>
                    <div class="row">
                                <!-- Columna izquierda para la imagen -->
                                <div class="col-md-6">
                                        <div class="pl-5 pt-4 ">
                                                <div class="pl-1" style="width:80%">';
                                                if($rows[0]["foto"]==""){
                                                $response = $response. '<img src="img/undraw_profile.svg" alt="Imagen" class="image-container rounded-circle "
                                                onclick="triggerFileInput('.$_POST['id'].')">';
                                                }else{
                                                    $response = $response. '<img src="img/undraw_profile.svg" alt="Imagen" class="image-container rounded-circle " onclick="triggerFileInput('.$_POST['id'].')">';
                                                }
                                                $response = $response. ' 
                                                </div>
                                        </div>
                                </div>
                                <!-- Columna derecha con 4 apartados -->
                                <div class="col-md-6">
                                    <form>
                                        <div class="form-group">
                                        <label for="campus">Campus</label>
                                         <select class="form-control" id="campus" >';

                                foreach($planteles as $row){
                                    if($row["id"]==$rows[0]["campus"]){
                                        $response=$response.' <option value="'.$row["id"].'" selected>'.$row["nombre"].'</option>';
                                    }else{
                                        $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                    }
                                    
                                }
                                   
                               $response=$response.' </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="rol">ROL</label>
                                            <select class="form-control" id="rol" >';

                                foreach($roles as $row){
                                    if($row["id"]==$rows[0]["rol"]){
                                        $response=$response.' <option value="'.$row["id"].'" selected>'.$row["rol"].'</option>';
                                    }else{
                                        $response=$response.' <option value="'.$row["id"].'">'.$row["rol"].'</option>';
                                    }
                                    
                                }
                                   
                               $response=$response.' </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Parte inferior con scroll y 10 cuadros -->
                            <div class="row mt-4 container">
                        <h3>
                        Permisos
                        </h3>
                        </div>
                            <div class="scrollable">
                            <div class="row">

                                 <div class="row container">
                        <div class="col-md-4">

                        <div class="custom-control custom-switch">';
                                    if($permisos[0]["chat"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p1" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p1">';
                                    }
                               $response=$response.'
                        <label class="custom-control-label" for="p1">Chat</label>
                        </div>
                        
                        <div class="custom-control custom-switch">';
                                    if($permisos[0]["asist"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p2" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p2">';
                                    }
                               $response=$response.'
                        <label class="custom-control-label" for="p2">Asistencia</label>
                        </div>
                        <div class="custom-control custom-switch">';
                                    if($permisos[0]["noticias"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p3" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p3">';
                                    }
                               $response=$response.'
                        <label class="custom-control-label" for="p3">Noticias</label>
                        </div>

                         <div class="custom-control custom-switch">';
                                    if($permisos[0]["evaluaciones"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p10" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p10">';
                                    }
                               $response=$response.'
                                <label class="custom-control-label" for="p10">Evaluaciones</label>
                                </div>
                                <div class="custom-control custom-switch">';
                                    if($permisos[0]["capacitaciones"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p11" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p11">';
                                    }
                               $response=$response.'
                                <label class="custom-control-label" for="p11">Capacitaciones</label>
                                </div>
                        </div>
                                 <div class="col-md-4">
                        <div class="custom-control custom-switch">';
                                    if($permisos[0]["calendario"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p4" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p4">';
                                    }
                               $response=$response.'
                        <label class="custom-control-label" for="p4">Calendario</label>
                        </div>
                        <div class="custom-control custom-switch">';
                                    if($permisos[0]["alertas"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p5" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p5">';
                                    }
                               $response=$response.'
                        <label class="custom-control-label" for="p5">Alertas</label>
                        </div>
                        <div class="custom-control custom-switch">';
                                    if($permisos[0]["blog"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p6" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p6">';
                                    }
                               $response=$response.'
                        <label class="custom-control-label" for="p6">Blog</label>
                        </div>

                        <div class="custom-control custom-switch">';
                                    if($permisos[0]["ia"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p12" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p12">';
                                    }
                               $response=$response.'
                                <label class="custom-control-label" for="p12">I.A.</label>
                                </div>
                                
                                <div class="custom-control custom-switch">';
                                    if($permisos[0]["kpi"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p13" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p13">';
                                    }
                               $response=$response.'
                                <label class="custom-control-label" for="p13">KPI´S</label>
                                </div>
                        </div>
                            <div class="col-md-4">
                                <div class="custom-control custom-switch">';
                                    if($permisos[0]["reportes"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p7" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p7">';
                                    }
                               $response=$response.'
                                <label class="custom-control-label" for="p7">Reportes</label>
                                </div>
                                <div class="custom-control custom-switch">';
                                    if($permisos[0]["tienda"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p8" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p8">';
                                    }
                               $response=$response.'
                                <label class="custom-control-label" for="p8">Tienda</label>
                                </div>
                                <div class="custom-control custom-switch">';
                                    if($permisos[0]["notas"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p9" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p9">';
                                    }
                               $response=$response.'
                                <label class="custom-control-label" for="p9">Notas</label>
                                </div>

                                <div class="custom-control custom-switch">';
                                    if($permisos[0]["reuniones"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p14" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p14">';
                                    }
                               $response=$response.'
                                <label class="custom-control-label" for="p14">Reuniones</label>
                                </div>

                                <div class="custom-control custom-switch">';
                                    if($permisos[0]["otros"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p15" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="p15">';
                                    }
                               $response=$response.'
                                <label class="custom-control-label" for="p15">Otros</label>
                                </div>
                            </div>
                        </div>


                            </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-warning" onclick="correo('.$rows[0]["id"] .')">Enviar correo</button>';
                            if($rows[0]["status"]== 1){
                                $response=$response.' <button type="button" class="btn btn-danger" onclick="desactivarUsuario('.$rows[0]["id"] .')">Desactivar Usuario</button>';

                            }else{
                                $response=$response.' <button type="button" class="btn btn-success" onclick="activarUsuario('.$rows[0]["id"] .')">Activar Usuario</button>';
                            }
                           
                            $response=$response.'<button type="button" class="btn btn-primary" onclick="actualizarUsuario('.$rows[0]["id"] .')">Guardar Cambios</button>
                        </div>';
                        echo $response;
        break;

        case 2:

        $stmt = $dbconn->prepare("SELECT * FROM roles");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $dbconn->prepare("SELECT * FROM planteles where status=1");
        $stmt2->execute();
        $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="AgregarUsuario">Agregar Usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                        <div class="mb-3 pb-3">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0" style="width: 0%" id="progress"></div>
                            </div>
                        </div>

                        <div>
                        <input type="number" value=0 class="d-none" id="progressnumber">
                        </div>
                        <!-- Formulario de Prospecto -->
                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                        <!-- Primera Fila: Nombre, Apellido Paterno, Apellido Materno -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" required > 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellidoPaterno">Apellido Paterno</label>
                                <input type="text" class="form-control" id="apellidoPaterno" required >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellidoMaterno">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellidoMaterno" required >
                            </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Segunda Fila: Nivel (select), Modalidad (select), Ciclo -->
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="rol">Rol</label>
                                <select class="form-control" id="rol" required >
                                <option value="0">Seleccionar rol</option>';

                                foreach($rows as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["rol"].'</option>';
                                }

                                    
                                   
                               $response=$response.' </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="campus">Campus</label>
                                <select class="form-control" id="campus" required >
                                <option value="0">Seleccionar campus</option>';

                                foreach($rows2 as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                }

                                    
                                   
                               $response=$response.' </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="usuario">Usuario</label>
                                            <input type="text" class="form-control" id="usuario" required >
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Tercera Fila: Teléfono, Correo Electrónico -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono</label>
                                            <input type="text" class="form-control" id="telefono" maxlength="10" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" required >
                                        </div>
                                    </div>
                                </div>

                            
                        </div> <div class="row ml-4 container">
                        <h3>
                        Permisos
                        </h3>
                        </div>
                        <div class="row m-3 container">
                       <div class="col-md-4">
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p1">
                            <label class="custom-control-label" for="p1">Chat</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p2">
                            <label class="custom-control-label" for="p2">Asistencia</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p3">
                            <label class="custom-control-label" for="p3">Noticias</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p10">
                            <label class="custom-control-label" for="p10">Evaluaciones</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p11">
                            <label class="custom-control-label" for="p11">Capacitaciones</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p4">
                            <label class="custom-control-label" for="p4">Calendario</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p5">
                            <label class="custom-control-label" for="p5">Alertas</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p6">
                            <label class="custom-control-label" for="p6">Blog</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p12">
                            <label class="custom-control-label" for="p12">I.A.</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p13">
                            <label class="custom-control-label" for="p13">KPI´S</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p7">
                            <label class="custom-control-label" for="p7">Reportes</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p8">
                            <label class="custom-control-label" for="p8">Tienda</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p9">
                            <label class="custom-control-label" for="p9">Notas</label>
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p14">
                            <label class="custom-control-label" for="p14">Reuniones</label>s
                            </div>
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="p15">
                            <label class="custom-control-label" for="p15">Otros</label>
                            </div>
                            </div>

                        </div>


                    <div class="row m-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="1">Código Postal</label>
                            <div class="input-group ">
                                <input type="text" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="5" id="1" autocomplete="off">
                                <span class="input-group-text" id="basic-addon2" style="background-color: #2e59d9;" onclick="codigo()"><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="2">Calle</label>
                            <input type="text" class="form-control" id="2" autocomplete="off" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="6">Numero ext.</label>
                            <input type="text" class="form-control" id="6" autocomplete="off" data-toggle="tooltip" data-placement="top" title="Si no cuenta con numero, ingresa 0" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="7">Numero Int.</label>
                            <input type="text" class="form-control" id="7" autocomplete="off" data-toggle="tooltip" data-placement="top" title="Si no cuenta con numero, ingresa 0" >
                        </div>
                    </div>
                </div>


                 <div class="row m-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="3">Localidad</label>
                            <select class="form-control" id="3" >
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="4">Municipio</label>
                            <input type="text" class="form-control" id="4" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="5">Estado</label>
                            <input type="text" class="form-control" id="5" disabled>
                        </div>
                    </div>
                </div>

                </form>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="saveProspectBtn" onclick="guardarUsuario()">Agregar usuario</button>
                        </div>';
                        echo $response;
        break;

        case 3:
            $nombre=$_POST["nombre"];
            $apellidoPaterno=$_POST["apellidoPaterno"];
            $apellidoMaterno=$_POST["apellidoMaterno"];
            $rol=$_POST["rol"];
            $campus=$_POST["campus"];
            $usuario=$_POST["usuario"];
            $telefono=$_POST["telefono"];
            $correo=$_POST["correo"];
            $direccion=$_POST["direccion"];

            $chat=$_POST["p1"];
            $asistencia=$_POST["p2"];
            $noticias=$_POST["p3"];

            $calendario=$_POST["p4"];
            $alertas=$_POST["p5"];-
            $blog=$_POST["p6"];

            $reportes=$_POST["p7"];
            $tienda=$_POST["p8"];
            $notas=$_POST["p9"];

            $evaluaciones=$_POST["p10"];
            $capacitaciones=$_POST["p11"];
            $ia=$_POST["p12"];

            $kpi=$_POST["p13"];
            $reuniones=$_POST["p14"];
            $otros=$_POST["p15"];

            $cp=$_POST["cp"];
            $calle=$_POST["calle"];
            $numext=$_POST["numext"];
            $numint=$_POST["numint"];
            $localidad=str_replace("-"," ",substr($_POST["localidad"],0,-1));
            $municipio=$_POST["municipio"];
            $estado=$_POST["estado"];

            $codigo="";


            try{

                        $query = "SELECT * from roles where id='".$rol."' ";
                        $exc_query = $dbconn->prepare($query);
                        $exc_query->execute();
                        $rrol = $exc_query->fetchAll(PDO::FETCH_ASSOC);

                        $query = "SELECT * from planteles where id='".$campus."' ";
                        $exc_query = $dbconn->prepare($query);
                        $exc_query->execute();
                        $descamp = $exc_query->fetchAll(PDO::FETCH_ASSOC);

                        $codigo=$descamp[0]["codigo"]."-".$rrol[0]["nomenclatura"];

                        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!#$%&?';
                        $palabra = '';
                        $max = strlen($caracteres) - 1;

                        for ($i = 0; $i < 6; $i++) {
                            $palabra .= $caracteres[random_int(0, $max)];
                        }    


                $query = "INSERT into usuarios (nombre ,apellidoP, apellidoM, cuenta, correo,password, telefono,campus,status,rol,cp,calle,numeroext,numeroint,localidad,municipio,estado,qr) VALUES
                ('".$nombre."','".$apellidoPaterno."','".$apellidoMaterno."','".$usuario."','".$correo."','".sha1($palabra)."','".$telefono."','".$campus."',1,'".$rol."','".$cp."','".$calle."','".$numext."','".$numint."','".$localidad."','".$municipio."','".$estado."','".$codigo."')";
                    $exc_query = $dbconn->prepare($query);
                    $exc_query->execute();

                        $query = "SELECT * from usuarios order by id desc limit 1 ";
                        $exc_query = $dbconn->prepare($query);
                        $exc_query->execute();
                        $cont = $exc_query->fetchAll(PDO::FETCH_ASSOC);

                        $query = "INSERT into permisos (usuario,chat,asist,noticias,calendario,alertas,blog,reportes,tienda,notas,evaluaciones,capacitaciones,ia,kpi,reuniones,otros)
                        values ('".$cont[0]["id"]."','".$chat."','".$asistencia."','".$noticias."','".$calendario."','".$alertas."','".$blog."','".$reportes."','".$tienda."','".$notas."','".$evaluaciones."','".$capacitaciones."','".$ia."','".$kpi."','".$reuniones."','".$otros."')";
                            $exc_query = $dbconn->prepare($query);
                            $exc_query->execute();

                        $query = "INSERT into log (usuario,descripcion,hora)  values ('".$sesion[0]."','CREACIÓN DE USUARIO EL ID: ".$cont[0]["id"]."',NOW())";
                        $exc_query = $dbconn->prepare($query);
                        $exc_query->execute();

                        if(header('Location: ../librerias/procesamiento/correoprospecto.php?contenido='.$cont[0]["id"].'|'.$palabra.'')==1){
                            echo $response=1;
                        }


            } catch (Error $e) {
                echo $response = 0;
            }

        break;

        case 4:

        $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Agregar Plantel</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <!-- Formulario de Prospecto -->
                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" required> 
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="ubicacion">Ubicación</label>
                                                <input type="text" class="form-control" id="ubicacion" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="razon">Razón social</label>
                                                <input type="text" class="form-control" id="razon" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="nomen">Nomenclatura</label>
                                                <input type="text" class="form-control" id="nomen" required>
                                            </div>
                                        </div>
                             </div>
                        
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="saveProspectBtn" onclick="guardarPlantel()">Agregar plantel</button>
                        </div>';
                        echo $response;
        break;    


        case 5:
            $nombre=strtoupper($_POST["nombre"]);
            $ubicacion=strtoupper($_POST["ubicacion"]);
            $razon=strtoupper($_POST["razon"]);
            $nomen=strtoupper($_POST["nomen"]);

            try{
                $query = "INSERT planteles VALUES ('','".$nombre."','".$ubicacion."','".$razon."','".$nomen."','1','')";
                    $exc_query = $dbconn->prepare($query);
                    $exc_query->execute();

                    $query = "INSERT into log (usuario,descripcion,hora)  values ('".$sesion[0]."','CREACIÓN DE PLANTEL: ".$nombre."',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();
                    echo $response = 1;
            } catch (Error $e) {
                
                //file_put_contents('../include/log.txt', print_r("error ---------------".$e, true), FILE_APPEND);
                echo $response = 0;
            }
 
        break;

        case 6:
            $id=$_POST["id"];
            $nombre=strtoupper($_POST["nombre"]);
            $ubicacion=strtoupper($_POST["ubicacion"]);
            $razon=strtoupper($_POST["razon"]);
            $nomen=strtoupper($_POST["nomen"]);
            $status=$_POST["status"];

            try{
               $query = "UPDATE planteles SET nombre = '".$nombre."', ubicacion = '".$ubicacion."',  razon_social = '".$razon."', nomenclatura = '".$nomen."', status = '".$status."'  WHERE id = '".$id."'" ; 
				$consulta = $dbconn->prepare($query);
				$consulta->execute();                
                echo $response = 1;
            } catch (Error $e) {
                //file_put_contents('../include/log.txt', print_r("error ---------------".$e, true), FILE_APPEND);
                echo $response = 0;
            }
 
        break;

         case 7:
            $id=$_POST["id"];

            $nombre=$_POST["nombre"];
            $apellidoPaterno=$_POST["apellidoPaterno"];
            $apellidoMaterno=$_POST["apellidoMaterno"];
            $rol=$_POST["rol"];
            $campus=$_POST["campus"];
            $usuario=$_POST["usuario"];
            // $password=$_POST["password"];
            $telefono=$_POST["telefono"];
            $correo=$_POST["correo"];
            $direccion=$_POST["direccion"];

            $chat=$_POST["p1"];
            $asistencia=$_POST["p2"];
            $noticias=$_POST["p3"];

            $calendario=$_POST["p4"];
            $alertas=$_POST["p5"];-
            $blog=$_POST["p6"];

            $reportes=$_POST["p7"];
            $tienda=$_POST["p8"];
            $notas=$_POST["p9"];

            $evaluaciones=$_POST["p10"];
            $capacitaciones=$_POST["p11"];
            $ia=$_POST["p12"];

            $kpi=$_POST["p13"];
            $reuniones=$_POST["p14"];
            $otros=$_POST["p15"];

            
            $cp=$_POST["cp"];
            $calle=$_POST["calle"];
            $numext=$_POST["numext"];
            $numint=$_POST["numint"];

            if(substr($_POST["localidad"],-1)=="-"){
            $localidad=str_replace("-"," ",substr($_POST["localidad"],0,-1));

            }else{
            $localidad=$_POST["localidad"];

            }

            $municipio=$_POST["municipio"];
            $estado=$_POST["estado"];

            try{
                        $query = "SELECT * from roles where id='".$rol."' ";
                        $exc_query = $dbconn->prepare($query);
                        $exc_query->execute();
                        $rrol = $exc_query->fetchAll(PDO::FETCH_ASSOC);

                        $query = "SELECT * from planteles where id='".$campus."' ";
                        $exc_query = $dbconn->prepare($query);
                        $exc_query->execute();
                        $descamp = $exc_query->fetchAll(PDO::FETCH_ASSOC);

                        $codigo=$descamp[0]["codigo"]."-".$rrol[0]["nomenclatura"];

                            $query = "UPDATE usuarios set nombre= '".$nombre."',apellidoP= '".$apellidoPaterno."',apellidoM= '".$apellidoMaterno."',cuenta= '".$usuario."',correo= '".$correo."',telefono= '".$telefono."',campus= '".$campus."',rol= '".$rol."',cp= '".$cp."',calle= '".$calle."',numeroext= '".$numext."',numeroint= '".$numint."',localidad= '".$localidad."',municipio= '".$municipio."',estado= '".$estado."',qr= '".$codigo."' WHERE id='".$id."'";
                            $exc_query = $dbconn->prepare($query);
                            $exc_query->execute();

                            $query = "UPDATE permisos set chat= '".$chat."',asist= '".$asistencia."',noticias= '".$noticias."',calendario= '".$calendario."',alertas= '".$alertas."',blog= '".$blog."',reportes= '".$reportes."',tienda= '".$tienda."',notas= '".$notas."',evaluaciones= '".$evaluaciones."',capacitaciones= '".$capacitaciones."',ia= '".$ia."',kpi= '".$kpi."',reuniones= '".$reuniones."',otros= '".$otros."' WHERE usuario='".$id."'";
                            $exc_query = $dbconn->prepare($query);
                            $exc_query->execute();

                    echo $response = 1;
            } catch (Error $e) {
                echo $response = 0;
            }

        break;

        case 8:

        $id=$_POST["id"];
        $stmt = $dbconn->prepare("SELECT c.id as idconcepto,c.nombre as concepto,c.monto,c.descuento,c.status,p.nombre as plan,p.fecha_limite,p.status,p.alumnos FROM plan_pagos p LEFT JOIN plan_pagos_conceptos c ON c.plan=p.id where p.plantel='".$id."'");
                    $stmt->execute();
                    $conceptos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response='
                    <div class="modal-header">
                            <h5 class="modal-title m-0 font-weight-bold text-primary" id="addProspectModalLabel" >Plan de pago: '.$conceptos[0]["plan"].'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <!-- Formulario de Prospecto -->
                        <form id="prospectForm" autocomplete="off" >
                         <div class="container-fluid">
                                <table id="tconceptos" class="table table-striped nowrap table-hover">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th>Monto</th>
                                            <th>Descuento</th>
                                            <th>Status</th>
                                            <th><i class="fa-solid fa-trash-can"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                    foreach ($conceptos as $row) {
                                          $response=$response.'<tr>
                                                <td>'.$row["idconcepto"].'</td>
                                                <td>'.$row["concepto"].'</td>
                                                <td>'.$row["monto"].'</td>
                                                <td>'.$row["descuento"].'</td>
                                                <td><div class="custom-control custom-switch">';
                                    if($row["status"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="status" checked>';
                                    }else{
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="status">';
                                    }
                               $response=$response.'
                        </div></td>
                                                <td><i class="fa-solid fa-trash-can" style="color: #ea6c6c;"></i></td>
                                            </tr>';
                                        }
                                    $response=$response.'
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-success" id="saveProspectBtn" onclick="agregarConcepto('.$id.')">Administrar concepto</button>
                            <button type="button" class="btn btn-primary" id="saveProspectBtn" onclick="guardarConceptosPlantel()">Guardar cambios</button>
                        </div>';
                        echo $response;
        break;   
        
         case 9: 
             $stmt = $dbconn->prepare("SELECT * from concurrencia");
                    $stmt->execute();
                    $concurrencia = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $dbconn->prepare("SELECT * from descuentos");
                    $stmt->execute();
                    $descuentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                     $id=$_POST["id"];
        
            $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Agregar Plantel</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <!-- Formulario de Prospecto -->
                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" required> 
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                        <label for="descuento">Descuento</label>
                                         <select class="form-control" id="descuento" >';

                                foreach($descuentos as $row){
                                        $response=$response.' <option value="'.$row["id"].'">'.$row["descuento"].'</option>';
                                }
                               $response=$response.' </select>
                                        </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monto">Monto</label>
                                                <input type="number" class="form-control" id="monto" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        <div class="form-group">
                                        <label for="concurrencia">Concurrencia</label>
                                         <select class="form-control" id="concurrencia" >';

                                foreach($concurrencia as $row){
                                        $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                }
                               $response=$response.' </select>
                                        </div>
                                        </div>
                             </div>
                        
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="saveProspectBtn" onclick="guardarConcepto('.$id.')">Agregar concepto</button>
                        </div>';
                        echo $response;
       
        break;

        case 10:

        break;

        
        case 11:
 
        break;

        case 12:

        break;

        case 13:
 
        break;

        case 14:
 
                    $id=$_POST["plantel"];

                    $stmt = $dbconn->prepare("SELECT * FROM niveles where status=1");
                    $stmt->execute();
                    $niveles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $stmt = $dbconn->prepare("SELECT * FROM modalidades where status=1");
                    $stmt->execute();
                    $modalidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $stmt = $dbconn->prepare("SELECT * FROM conceptos where status=1 and plantel='".$id."'");
                    $stmt->execute();
                    $conceptos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Creacion de plan de pagos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" required> 
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="dia">Dia de pago</label>
                                                <input type="number" class="form-control" id="dia" required min="1" max="31">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="nivel">Nivel</label>
                                                <select class="form-control" id="nivel" required>';

                                foreach($niveles as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                }

                                    
                                   
                               $response=$response.' </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="modalidad">Modalidad</label>
                                                <select class="form-control" id="modalidad" required>';

                                foreach($modalidades as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                }

                                    
                                   
                               $response=$response.' </select>
                                            </div>
                                        </div>
                             </div>



                              <div class="row m-2">
                                    <table id="tcont" class="table table-striped table-hover nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">Concepto</th>
                                            <th style="text-align:center">Monto</th>
                                            <th style="text-align:center">Descuento</th>
                                            <th style="text-align:center">Agregar</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                        foreach ($conceptos as $row) {
                                            $response=$response.'<tr>
                                                <td style="text-align:center">'.$row["nombre"].'</td>
                                                <td style="text-align:center">'.$row["monto"].'</td>
                                                <td style="text-align:center">'.$row["descuento"].'</td>
                                                <td style="text-align:center">
                                                <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch'.$row["id"].'" name="concepto" value="'.$row["id"].'">
                                                <label class="custom-control-label" for="customSwitch'.$row["id"].'"></label>
                                                </div>
                                                </td>
                                            </tr>';
                                        } 

                                        $response=$response.'
                                    </tbody>
                                    </table>
                             </div>
                        
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="saveProspectBtn" onclick="guardarPlan('.$id.')">Crear plan</button>
                        </div>';
                        echo $response;
        break;    



        case 15:
            $nombre=strtoupper($_POST["nombre"]);
            $plantel=$_POST["plantel"];
            $dia=$_POST["dia"];
            $nivel=$_POST["nivel"];
            $modalidad=$_POST["modalidad"];
            $conceptos=$_POST["conceptos"];

            try{
                    $exc_query = $dbconn->prepare("INSERT plan_pagos VALUES ('','".$nombre."','".$plantel."','".$dia."','".$nivel."','".$modalidad."','1')");
                    $exc_query->execute();


                    $exc_query = $dbconn->prepare("select id from plan_pagos order by id desc limit 1");
                    $exc_query->execute();
                    $plan = $exc_query->fetchAll(PDO::FETCH_ASSOC);

                    $concepto = explode("|",$conceptos);

                    foreach($concepto as $concept){

                        $exc_query = $dbconn->prepare("INSERT plan_pagos_conceptos VALUES ('','".$plan[0]["id"]."','".$concept."','".$plantel."')");
                        $exc_query->execute();
                    }

                    $query = "INSERT into log (usuario,descripcion,hora)  values ('".$sesion[0]."','CREACIÓN DE PAGO EL ID: ".$plan[0]["id"]." CON CONCEPTOS: ".$conceptos."',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();


                    echo $response = 1;
            } catch (Error $e) {
                
                //file_put_contents('../include/log.txt', print_r("error ---------------".$e, true), FILE_APPEND);
                echo $response = 0;
            }
 
        break;

        case 16:
            $plantel=$_POST["id"];
            $nombre=$_POST["nombre"];
            $descuento=$_POST["descuento"];
            $monto=$_POST["monto"];
            $concurrencia=$_POST["concurrencia"];

            try{
                $query = "INSERT conceptos VALUES ('','".$plantel."','".$nombre."','".$descuento."','".$monto."','".$concurrencia."','1')";
                    $exc_query = $dbconn->prepare($query);
                    $exc_query->execute();

                    $query = "INSERT into log (usuario,descripcion,hora)  values ('".$sesion[0]."','CREACIÓN DE CONCEPTO: ".$nombre."',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();
                    echo $response = 1;
            } catch (Error $e) {
                
                //file_put_contents('../include/log.txt', print_r("error ---------------".$e, true), FILE_APPEND);
                echo $response = 0;
            }
            break;


              case 17:
    $stmt = $dbconn->prepare("SELECT * FROM inventario_indices WHERE status=1");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $dbconn->prepare("SELECT * FROM inventario_medidas WHERE status=1");
    $stmt2->execute();
    $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $response = '
        <div class="modal-header">
            <h5 class="modal-title" id="addProspectModalLabel">Agregar Artículo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="prospectForm" autocomplete="off" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control" id="marca" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <select class="form-control" id="categoria" required>';

                            foreach ($rows as $row) {
                                $response .= '<option value="' . $row["id"] . '">' . $row["categoria"] . '</option>';
                            }

    $response .= '          </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="medida">Unidad de medida</label>
                            <select class="form-control" id="medida" required>';

                            foreach ($rows2 as $row) {
                                $response .= '<option value="' . $row["id"] . '">' . $row["medida"] . '</option>';
                            }

    $response .= '          </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="foto_articulo">Foto del artículo <small class="text-muted">(opcional)</small></label>
                            <input type="file" class="form-control-file" id="foto_articulo" accept="image/*"
                                   onchange="previewFotoArticulo(this)">
                            <div id="preview_foto_articulo" class="mt-2 text-center"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="guardarArticulo()">Agregar artículo</button>
        </div>';

    echo $response;
    break;

        case 18:
    $nombre    = strtoupper(trim($_POST["nombre"]));
    $marca     = strtoupper(trim($_POST["marca"]));
    $categoria = (int)$_POST["categoria"];
    $medida    = (int)$_POST["medida"];

    try {
        $dbconn->beginTransaction();

        // ── 1. Incrementar conteo PRIMERO (operación atómica → sin duplicados) ──
        $dbconn->prepare("UPDATE inventario_indices SET conteo = conteo + 1 WHERE id = ?")
               ->execute([$categoria]);

        // ── 2. Leer el código con el conteo YA incrementado ──────────────────
        $stmt = $dbconn->prepare("SELECT CONCAT(codigo, '-', conteo) AS codigo FROM inventario_indices WHERE id = ?");
        $stmt->execute([$categoria]);
        $nuevo_codigo = $stmt->fetchColumn();  // ej: "ACTI-42"

        // ── 3. Insertar artículo (prepared statement, sin concatenación) ──────
        $dbconn->prepare("INSERT INTO inventario (area, codigo, nombre, medida, marca, status, foto)
                          VALUES (?, ?, ?, ?, ?, 1, '')")
               ->execute([$categoria, $nuevo_codigo, $nombre, $medida, $marca]);

        $nuevo_id = $dbconn->lastInsertId();

        // ── 4. Procesar foto si se subió ──────────────────────────────────────
        $nombre_foto = '';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

            $mimeMap = [
                'image/jpeg' => 'jpg',
                'image/jpg'  => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp',
            ];
            $ext = $mimeMap[$_FILES['foto']['type']] ?? 'jpg';

            // Nombre idéntico al que usa el script de reemplazo de foto
            $nombre_foto = $nuevo_codigo . '.' . $ext;
            $destino     = "../img/categorias/inventario/" . $nombre_foto;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $dbconn->prepare("UPDATE inventario SET foto = ? WHERE id = ?")
                       ->execute([$nombre_foto, $nuevo_id]);
            }
        }

        // ── 5. Registrar movimiento de alta ───────────────────────────────────
        $dbconn->prepare("INSERT INTO inventario_movimientos
                            (articulo, usuario, tipo, campusIngreso, campusEgreso,
                             cantidad, comentario, hora, usuario_movimiento, usuario_final)
                          VALUES (?, ?, '0', '0', '0', '0', 'ALTA DE ARTICULO', NOW(), ?, 0)")
               ->execute([$nuevo_id, $sesion[0], $sesion[0]]);

        $dbconn->commit();
        echo 1;

    } catch (Exception $e) {
        $dbconn->rollBack();
        echo 0;
    }
    break;


        case 19:
        $articulo=$_POST["articulo"];
        $campus=$_POST["campus"];
        $stmt = $dbconn->prepare("SELECT * FROM inventario where id='".$articulo."'");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $dbconn->prepare("SELECT * FROM planteles where id!=8");
        $stmt2->execute();
        $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $stmt3 = $dbconn->prepare("SELECT id,campus,concat(nombre,' ',apellidoP,' ',apellidoM) as nombre FROM usuarios where campus='".$campus."'");
        $stmt3->execute();
        $rows3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

         $stmt4 = $dbconn->prepare("SELECT id,nombre FROM inventario_motivos where tipo=2");
        $stmt4->execute();
        $rows4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

        $stmt5 = $dbconn->prepare("SELECT id,nombre FROM inventario_motivos where tipo=1");
        $stmt5->execute();
        $rows5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);
        $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Modificación de: '.$rows[0]["nombre"].'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        
                                        <div class="row">
                                            <!-- Columna izquierda para la imagen -->
                                            <div class="col-md-12">
                                                    <div class="row p-5  justify-content-evenly">';
                                                            if($rows[0]["foto"]!=""){
                                                            $response = $response. '<img <img src="img/categorias/inventario/'.$rows[0]["foto"].'?v='.(@filemtime(__DIR__."/../img/categorias/inventario/".$rows[0]["foto"]) ?: time()).'"alt="Imagen" class="image-container rounded mx-auto d-block w-50">';
                                                            }else{
                                                                $response = $response. '<img src="img/undraw_profile.svg" alt="Imagen" class="image-container rounded mx-auto d-block w-50">';
                                                            }
                                                            $response = $response. '
                                                    </div>
                                            </div>
                                        </div>
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" id="alta">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Ingresar stock
                                        </button>
                                    </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="alta" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="icantidad">Cantidad a ingresar</label>
                                                <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 && event.charCode != 45" class="form-control" id="icantidad" required> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="icomentarios">Motivo de ingreso:</label>
                                                    <select class="form-control" id="icomentarios" required>
                                                    <option value="0">Selecciona motivo</option>';

                                            foreach($rows5 as $row){
                                                $response=$response.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                            }
                                        $response=$response.'
                                        </select>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-success" id="saveProspectBtn" onclick="ingresoArticulo('.$campus.','.$articulo.')">Continuar</button>
                                    </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="requerir">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Requerir material
                                        </button>
                                    </h5>
                                    </div>
                                        <div id="collapseTwo" class="collapse" aria-labelledby="requerir" data-parent="#accordion">
                                                <div class="card-body">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label for="ecantidad">Cantidad a requerir</label>
                                                                    <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 && event.charCode != 45" class="form-control" id="ecantidad" required> 
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                    <select class="form-control" id="usuariofinal" required>';
                                                foreach($rows3 as $row){
                                                    $response=$response.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                                }
                                            $response=$response.'</select>
                                                                    </div>
                                                                </div>
                                                            </div>
  
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                    <label for="ecomentarios">Motivo:</label>
                                                                    <select class="form-control" id="ecomentarios" required>
                                                                        <option value="0">Seleccionar motivo</option>';
                                                                        foreach($rows4 as $row){
                                                                            $response=$response.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                                                        }
                                                                    $response=$response.'</select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <button type="button" class="btn btn-success" id="saveProspectBtn" onclick="egresoArticulo('.$campus.','.$articulo.')">Continuar</button>
                                                </div>
                                        </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Transferir
                                        </button>
                                    </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                    <div class="card-body">
                                     <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                            <label for="corigen">Transferir desde: </label>
                                            <select class="form-control" id="corigen" onchange="stockCampus('.$articulo.')" required>';
                                                foreach($rows2 as $row){
                                                    $response=$response.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                                }
                                            $response=$response.' <option value="8">CORPORATIVO</option></select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tcantidad">Stock actual</label>
                                                <input type="number" class="form-control" id="tcantidad" required> 
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                            <label for="creceptor">Campus receptor: </label>
                                            <select class="form-control" id="creceptor" required>';

                                            foreach($rows2 as $row){
                                                $response=$response.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                            }
                                        $response=$response.'
                                        <option value="8">CORPORATIVO</option></select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="tcomentarios">Comentarios</label>
                                                <textarea class="form-control" id="tcomentarios" rows="3" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-success" id="saveProspectBtn" onclick="transferenciaArticulo('.$articulo.')">Continuar</button>

                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>';
                        echo $response;
        break;
        
          


        case 20:
        $articulo=$_POST["articulo"];
        $campus=$_POST["campus"];
        $stmt = $dbconn->prepare("SELECT * FROM inventario where id='".$articulo."'");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if($campus== 1){
            $response=$row[0]["sanjuan"];
        }else if($campus== 2){
            $response=$row[0]["aculco"];
        }else if($campus== 3){
            $response=$row[0]["tecamac"];
        }else if($campus== 4){
            $response=$row[0]["atlacomulco"];
        }else if($campus== 5){
            $response=$row[0]["tepeji"];
        }else if($campus== 6){
            $response=$row[0]["nopala"];
        }else if($campus== 7){
            $response=$row[0]["enlinea"];
        }else if($campus== 8){
            $response=$row[0]["corporativo"];
        }else if($campus== 13){
            $response=$row[0]["sanjuan5"];
        }
        echo $response;
        break;


        case 21:
        $articulo=$_POST["articulo"];
        $campus=$_POST["campus"];
        $comentarios=$_POST["comentarios"];
        $stock=$_POST["cantidad"];
        $stmt = $dbconn->prepare("SELECT * FROM inventario where id='".$articulo."'");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        try{

        $stmt = $dbconn->prepare("SELECT *, COUNT(*) AS existencia FROM inventario_stock where articulo_id=? and plantel_id=?");
        $stmt->execute([$articulo,$campus]);
        $rows1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($rows1[0]["existencia"]!=0){
            $exc_query = $dbconn->prepare("UPDATE inventario_stock SET stock=stock+? WHERE plantel_id=? and articulo_id=?");
                $exc_query->execute([$stock,$campus,$articulo]);
                
                //SIGNIFICADO DE TIPO DE MOVIMIENTO
                //1 = INGRESO
                //2 = EGRESO
                //3 = TRANFERENCIA

                
        }else{

            $exc_query = $dbconn->prepare("INSERT INTO inventario_stock (stock,plantel_id,articulo_id) VALUES (?,?,?)");
                $exc_query->execute([$stock,$campus,$articulo]);
        }
            $query = "INSERT into inventario_movimientos (articulo,usuario,tipo,campusIngreso,campusEgreso,cantidad,comentario,hora,usuario_movimiento,usuario_final)
                values ('".$articulo."','".$sesion[0]."','1','".$campus."','0','".$stock."','".$comentarios."',NOW(),'".$sesion[0]."',0)";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();
                

            $response=1;
        }catch(Error $e){
            $response= 0;
        } 
       
        echo $response;
        break;

         case 22:
        $articulo=$_POST["articulo"];
        $campus=$_POST["campus"];
        $comentarios=$_POST["comentarios"];
        $cantidad=$_POST["cantidad"];
        $destino=$_POST["usuariofinal"];
        $stmt = $dbconn->prepare("SELECT * FROM inventario where id='".$articulo."'");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if($campus== 1){
            $scampus=$row[0]["sanjuan"];
            $ncampus="sanjuan";
        }else if($campus== 2){
            $scampus=$row[0]["aculco"];
            $ncampus="aculco";
        }else if($campus== 3){
            $scampus=$row[0]["tecamac"];
            $ncampus="tecamac";
        }else if($campus== 4){
            $scampus=$row[0]["atlacomulco"];
            $ncampus="atlacomulco";
        }else if($campus== 5){
            $scampus=$row[0]["tepeji"];
            $ncampus="tepeji";
        }else if($campus== 6){
            $scampus=$row[0]["nopala"];
            $ncampus="nopala";
        }else if($campus== 7){
            $scampus=$row[0]["enlinea"];
            $ncampus="enlinea";
        }else if($campus== 8){
            $scampus=$row[0]["corporativo"];
            $ncampus="corporativo";
        }else if($campus== 13){
            $scampus=$row[0]["sanjuan5"];
            $ncampus="sanjuan5";
        }

        if(($scampus-$cantidad) <0 ){
            $response=2;
        }else{
             try{
             $query = "UPDATE inventario SET ".$ncampus."=".$scampus."-".$cantidad." WHERE id='".$articulo."'";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();

                //SIGNIFICADO DE TIPO DE MOVIMIENTO
                //1 = INGRESO
                //2 = EGRESO
                //3 = TRANFERENCIA

                $query = "INSERT into inventario_movimientos (articulo,usuario,tipo,campusIngreso,campusEgreso,cantidad,comentario,hora,usuario_movimiento,usuario_final)
                values ('".$articulo."','".$sesion[0]."','2','0','".$campus."','".$cantidad."','".$comentarios."',NOW(),'".$sesion[0]."','".$destino."')";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();
            $response=1;
        }catch(Error $e){
            $response= 0;
        } 
        }
        echo $response;
        break;


        case 23:
        $articulo=$_POST["articulo"];
        $campus=$_POST["corigen"];
        $creceptor=$_POST["creceptor"];
        $comentarios=$_POST["comentarios"];
        $cantidad=$_POST["cantidad"];
        $stmt = $dbconn->prepare("SELECT * FROM inventario where id='".$articulo."'");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if($campus== 1){
            $scampus=$row[0]["sanjuan"];
            $ncampus="sanjuan";
        }else if($campus== 2){
            $scampus=$row[0]["aculco"];
            $ncampus="aculco";
        }else if($campus== 3){
            $scampus=$row[0]["tecamac"];
            $ncampus="tecamac";
        }else if($campus== 4){
            $scampus=$row[0]["atlacomulco"];
            $ncampus="atlacomulco";
        }else if($campus== 5){
            $scampus=$row[0]["tepeji"];
            $ncampus="tepeji";
        }else if($campus== 6){
            $scampus=$row[0]["nopala"];
            $ncampus="nopala";
        }else if($campus== 7){
            $scampus=$row[0]["enlinea"];
            $ncampus="enlinea";
        }else if($campus== 8){
            $scampus=$row[0]["corporativo"];
            $ncampus="corporativo";
        }else if($campus== 13){
            $scampus=$row[0]["sanjuan5"];
            $ncampus="sanjuan5";
        }

        if($creceptor== 1){
            $rscampus=$row[0]["sanjuan"];
            $rncampus="sanjuan";
        }else if($creceptor== 2){
            $rscampus=$row[0]["aculco"];
            $rncampus="aculco";
        }else if($creceptor== 3){
            $rscampus=$row[0]["tecamac"];
            $rncampus="tecamac";
        }else if($creceptor== 4){
            $rscampus=$row[0]["atlacomulco"];
            $rncampus="atlacomulco";
        }else if($creceptor== 5){
            $rscampus=$row[0]["tepeji"];
            $rncampus="tepeji";
        }else if($creceptor== 6){
            $rscampus=$row[0]["nopala"];
            $rncampus="nopala";
        }else if($creceptor== 7){
            $rscampus=$row[0]["enlinea"];
            $rncampus="enlinea";
        }else if($creceptor== 8){
            $rscampus=$row[0]["corporativo"];
            $rncampus="corporativo";
        }else if($creceptor== 13){
            $rscampus=$row[0]["sanjuan5"];
            $rncampus="sanjuan5";
        }


        if(($scampus-$cantidad) <0 ){
            $response=2;
        }else{
             try{
             $query = "UPDATE inventario SET ".$ncampus."=".$scampus."-".$cantidad.", ".$rncampus."=".$rscampus."+".$cantidad."  WHERE id='".$articulo."'";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();

                
                //SIGNIFICADO DE TIPO DE MOVIMIENTO DEL INVENTARIO

                //0 = ALTA DE ARTICULO
                //1 = INGRESO
                //2 = EGRESO
                //3 = TRANFERENCIA
                //4 = ACTUALIZACION DE DATOS
                //5 = BAJA DE ARTICULO

                $query = "INSERT into inventario_movimientos (articulo,usuario,tipo,campusIngreso,campusEgreso,cantidad,comentario,hora,usuario_movimiento,usuario_final)
                values ('".$articulo."','".$sesion[0]."','3','".$creceptor."','".$campus."','".$cantidad."','".$comentarios."',NOW(),'".$sesion[0]."','".$creceptor."')";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();
            $response=1;
        }catch(Error $e){
            $response= 0;
        } 
        }
        echo $response;
        break;


        case 24:
            $articulo=$_POST["articulo"];
             $stmt = $dbconn->prepare("SELECT 
                                        i.id, i.area, i.codigo, i.nombre, i.medida,
                                        COALESCE(SUM(CASE WHEN s.plantel_id = 1  THEN s.stock END), 0) AS `SJR`,
                                        COALESCE(SUM(CASE WHEN s.plantel_id = 13 THEN s.stock END), 0) AS `SJR5`,
                                        COALESCE(SUM(CASE WHEN s.plantel_id = 2  THEN s.stock END), 0) AS `ACULCO`,
                                        COALESCE(SUM(CASE WHEN s.plantel_id = 3  THEN s.stock END), 0) AS `TECAMAC`,
                                        COALESCE(SUM(CASE WHEN s.plantel_id = 4  THEN s.stock END), 0) AS `ATLACOMULCO`,
                                        COALESCE(SUM(CASE WHEN s.plantel_id = 5  THEN s.stock END), 0) AS `TEPEJI`,
                                        COALESCE(SUM(CASE WHEN s.plantel_id = 6  THEN s.stock END), 0) AS `NOPALA`,
                                        COALESCE(SUM(CASE WHEN s.plantel_id = 7  THEN s.stock END), 0) AS `EN LINEA`,
                                        COALESCE(SUM(CASE WHEN s.plantel_id = 8  THEN s.stock END), 0) AS `CORPORATIVO`,
                                        COALESCE(SUM(s.stock), 0) AS total,
                                        i.foto
                                    FROM inventario i
                                    LEFT JOIN inventario_stock s ON s.articulo_id = i.id
                                    WHERE i.id=?
                                    GROUP BY i.id, i.area, i.codigo, i.nombre, i.medida
                                    ORDER BY i.area, 
                                    SUBSTRING_INDEX(i.codigo, '-', 1),
                                    CAST(SUBSTRING_INDEX(i.codigo, '-', -1) AS UNSIGNED) ASC");
        $stmt->execute([$articulo]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response=' <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">ARTICULO: '.$rows[0]["nombre"].'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Parte superior -->
                                        <div class="row">
                                        <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" disabled  value="'.$rows[0]["nombre"].'"> 
                                        </div>
                                        </div>

                                        <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="existencias">Existencias</label>
                                            <input type="text" class="form-control" id="existencias" disabled  value="'.$rows[0]["total"].'"> 
                                        </div>
                                        </div>
                                        </div>

                            <div class="row">
                                <!-- Columna izquierda para la imagen -->
                                <div class="col-md-12">
                                        <div class="row mt-5 p-5  justify-content-evenly">';
                                                if($rows[0]["foto"]!=""){
                                                    if($rows[0]["area"]==10){
                                                $response = $response. '<img <img src="img/categorias/inventario/BIBL.jpeg" alt="Imagen" class="image-container rounded mx-auto d-block w-50" >';

                                                    }else{
                                                $response = $response. '<img <img src="img/categorias/inventario/'.$rows[0]["foto"].'?v='.(@filemtime(__DIR__."/../img/categorias/inventario/".$rows[0]["foto"]) ?: time()).'" alt="Imagen" class="image-container rounded mx-auto d-block w-50" >';

                                                    }
                                                }else{

                                                
                                                        if($rows[0]["area"]==10){
                                                $response = $response. '<img <img src="img/categorias/inventario/BIBL.jpeg" alt="Imagen" class="image-container rounded mx-auto d-block w-50" >';

                                                    }else{
                                                    $response = $response. '<img src="img/undraw_profile.svg" alt="Imagen" class="image-container rounded mx-auto d-block w-50" >';

                                                    }


                                                }
                                                $response = $response. '
                                        </div>
                                </div>
                            </div>
                            </div>


                            
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-danger" onclick="eliminarArticulo('.$rows[0]["id"] .')">Eliminar</button>
                            <button type="button" class="btn btn-primary" onclick="actualizarArticulo('.$rows[0]["id"] .')">Modificar</button>
                        </div>';
                        echo $response;
        break;

        case 25:
        

             $articulo=$_POST["articulo"];
             $stmt = $dbconn->prepare("SELECT * FROM inventario WHERE id='".$articulo."'");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response='
        <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Modificacion de foto para el articulo: '.$rows[0]["nombre"].'</h5>
      </div>
      <div class="modal-body">
      <form action="funciones/inventario_img.php" method="POST" enctype="multipart/form-data">

        <div style="height: 40px;">
            <button class="btn btn-primary" onclick="abrir()"><a ><i class="fa-solid fa-folder-open" style="color: #fafcff;"></i></a> </button>

            <div style="display: none;">
            <input name="archivo" id="archivo" type="file" accept="image/jpeg" required class="rellen"/>
            <input type="text" name="nfoto" id="nfoto" class="rellen" required value="'.$rows[0]["codigo"].'">
            <input type="text" name="idarticulo" id="idarticulo" class="rellen" required value="'.$rows[0]["id"].'">
            </div>
            
            <input type="submit" name="subir" value="Enviar" class=" btn btn-primary"/>
        </div>

    </form>
        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-danger" onclick="articulo('.$articulo.')">Cancelar</button>
                        </div>';
                        echo $response;
        break;


        case 26:
            $articulo=$_POST["articulo"];
            
            try{
                $query = "UPDATE inventario SET status='0' WHERE id='".$articulo."'";
                    $exc_query = $dbconn->prepare($query);
                    $exc_query->execute();

                    $query = "INSERT into inventario_movimientos (articulo,usuario,tipo,campusIngreso,campusEgreso,cantidad,comentario,hora,usuario_movimiento,usuario_final)
                values ('".$articulo."','".$sesion[0]."','5','0','0','0','BAJA DE ARTICULO',NOW(),'".$sesion[0]."',0)";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();
                    $response=1;
            }catch(Error $e){
                    $response=0;

            }
           
            echo $response;

        break;

       case 27:
    $articulo = (int)$_POST["articulo"];

    $stmt = $dbconn->prepare("SELECT * FROM inventario WHERE id = ?");
    $stmt->execute([$articulo]);
    $articulor = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $dbconn->prepare("SELECT * FROM inventario_indices WHERE status = 1");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $dbconn->prepare("SELECT * FROM inventario_medidas WHERE status = 1");
    $stmt2->execute();
    $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $response = '
        <div class="modal-header">
            <h5 class="modal-title">Modificación de artículo: ' . htmlspecialchars($articulor[0]["nombre"]) . '</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="prospectForm" autocomplete="off" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" required value="' . htmlspecialchars($articulor[0]["nombre"]) . '">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control" id="marca" required value="' . htmlspecialchars($articulor[0]["marca"]) . '">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <select class="form-control" id="categoria" required>';

                            foreach ($rows as $row) {
                                $sel = $articulor[0]["area"] == $row["id"] ? ' selected' : '';
                                $response .= '<option value="' . $row["id"] . '"' . $sel . '>' . htmlspecialchars($row["categoria"]) . '</option>';
                            }

    $response .= '          </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="medida">Unidad de medida</label>
                            <select class="form-control" id="medida" required>';

                            foreach ($rows2 as $row) {
                                $sel = $articulor[0]["medida"] == $row["id"] ? ' selected' : '';
                                $response .= '<option value="' . $row["id"] . '"' . $sel . '>' . htmlspecialchars($row["medida"]) . '</option>';
                            }

    $response .= '          </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Foto del artículo <small class="text-muted">(opcional — reemplaza la actual)</small></label>';
                            // Por esto:
                            if (!empty($articulor[0]["foto"])) {
                                $v = @filemtime(__DIR__ . "/../img/categorias/inventario/" . $articulor[0]["foto"]) ?: time();
                                $response .= '
                            <div class="mb-2 text-center">
                                <p class="text-muted small">Foto actual:</p>
                                <img src="img/categorias/inventario/' . htmlspecialchars($articulor[0]["foto"]) . '?v=' . $v . '"
                                    style="max-height:120px; border-radius:6px;">
                            </div>';
                            }

    $response .= '          
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="file" class="form-control-file" id="foto_articulo" accept="image/*"
                                   onchange="previewFotoArticulo(this)">
                            <div id="preview_foto_articulo" class="mt-2 text-center"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
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
            <button type="button" class="btn btn-secondary" onclick="articulo(' . $articulo . ')">Cancelar</button>
            <button type="button" class="btn btn-success" onclick="guardarActualizacionArticulo(' . $articulor[0]["id"] . ')">Guardar cambios</button>
        </div>';

    echo $response;
    break;


    case 28:
    $articulo    = (int)$_POST["articulo"];
    $nombre      = strtoupper(trim($_POST["nombre"]));
    $marca       = strtoupper(trim($_POST["marca"]));
    $categoria   = (int)$_POST["categoria"];
    $medida      = (int)$_POST["medida"];
    $comentarios = strtoupper(trim($_POST["comentarios"]));

    try {
        $dbconn->beginTransaction();

        $stmtCod = $dbconn->prepare("SELECT area FROM inventario WHERE id = ?");
            $stmtCod->execute([$articulo]);
            $area = $stmtCod->fetchColumn();

            if($area!=$categoria){
                // ── 1. Incrementar conteo PRIMERO (operación atómica → sin duplicados) ──
                $dbconn->prepare("UPDATE inventario_indices SET conteo = conteo + 1 WHERE id = ?")
                        ->execute([$categoria]);

                // ── 2. Leer el código con el conteo YA incrementado ──────────────────
                $stmt = $dbconn->prepare("SELECT CONCAT(codigo, '-', conteo) AS codigo FROM inventario_indices WHERE id = ?");
                $stmt->execute([$categoria]);
                $nuevo_codigo = $stmt->fetchColumn();  // ej: "ACTI-42"

                $dbconn->prepare("UPDATE inventario SET codigo = ? WHERE id = ?")
               ->execute([$nuevo_codigo,$articulo]);
            }


          



        $dbconn->prepare("UPDATE inventario SET nombre = ?, marca = ?, area = ?, medida = ? WHERE id = ?")
               ->execute([$nombre, $marca, $categoria, $medida, $articulo]);

        // ── Procesar foto si se subió ─────────────────────────────────────────
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

            // Leer el código actual para usarlo como nombre de archivo (igual que subefoto.php)
            $stmtCod = $dbconn->prepare("SELECT IF(
                                            NULLIF(TRIM(foto), '') IS NULL, 
                                            codigo, 
                                            SUBSTRING_INDEX(foto, '.', 1)
                                        ) AS codigo 
                                        FROM inventario 
                                        WHERE id = ?");
            $stmtCod->execute([$articulo]);
            $codigo_actual = $stmtCod->fetchColumn();

            // Extensión desde MIME type, ignorando el nombre original del archivo
            $mimeMap = [
                'image/jpeg' => 'jpg',
                'image/jpg'  => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp',
            ];
            $ext         = $mimeMap[$_FILES['foto']['type']] ?? 'jpg';
            $nombre_foto = $codigo_actual . '.' . $ext;
            $destino     = "../img/categorias/inventario/" . $nombre_foto;

            // Borrar foto anterior con diferente extensión (mismo que subefoto.php)
            foreach (['jpg', 'jpeg', 'png', 'gif', 'webp'] as $extVieja) {
                $vieja = "../img/categorias/inventario/" . $codigo_actual . "." . $extVieja;
                if (file_exists($vieja) && $vieja !== $destino) {
                    unlink($vieja);
                }
            }

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $dbconn->prepare("UPDATE inventario SET foto = ? WHERE id = ?")
                       ->execute([$nombre_foto, $articulo]);
            }
        }

        $dbconn->prepare("INSERT INTO inventario_movimientos
                            (articulo, usuario, tipo, campusIngreso, campusEgreso,
                             cantidad, comentario, hora, usuario_movimiento, usuario_final)
                          VALUES (?, ?, '4', '0', '0', '0', ?, NOW(), ?, 0)")
               ->execute([$articulo, $sesion[0], 'MODIFICACION DE ARTICULO: ' . $comentarios, $sesion[0]]);

        $dbconn->commit();
        echo 1;

    } catch (Exception $e) {
        $dbconn->rollBack();
        echo 0;
    }
    break;

        
            
    case 29:
        $articulo=$_POST["articulo"];
        $campus=$_POST["campus"];
        $stmt = $dbconn->prepare("SELECT * FROM inventario where id='".$articulo."'");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $dbconn->prepare("SELECT *, COUNT(*) AS existencia FROM inventario_stock where articulo_id=? and plantel_id=?");
        $stmt->execute([$articulo,$campus]);
        $rows1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $dbconn->prepare("SELECT * FROM planteles where id!=8");
        $stmt2->execute();
        $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $stmt3 = $dbconn->prepare("SELECT id,campus,concat(nombre,' ',apellidoP,' ',apellidoM) as nombre FROM usuarios where campus='".$campus."'");
        $stmt3->execute();
        $rows3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);


        $stmt4 = $dbconn->prepare("SELECT id,nombre FROM inventario_motivos where tipo=2");
        $stmt4->execute();
        $rows4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

        $stmt5 = $dbconn->prepare("SELECT id,nombre FROM inventario_motivos where tipo=1");
        $stmt5->execute();
        $rows5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);
        $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Modificación de: '.$rows[0]["nombre"].'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        
                                        <div class="row">
                                            <!-- Columna izquierda para la imagen -->
                                            <div class="col-md-12">
                                                    <div class="row p-5  justify-content-evenly">';
                                                            if($rows[0]["foto"]!=""){
                                                            $response = $response. '<img <img src="img/categorias/inventario/'.$rows[0]["foto"].'?v='.(@filemtime(__DIR__."/../img/categorias/inventario/".$rows[0]["foto"]) ?: time()).'"alt="Imagen" class="image-container rounded mx-auto d-block w-50">';
                                                            }else{
                                                                $response = $response. '<img src="img/undraw_profile.svg" alt="Imagen" class="image-container rounded mx-auto d-block w-50">';
                                                            }
                                                            $response = $response. '
                                                    </div>
                                            </div>
                                        </div>
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" id="alta">
                                    <h5 class="mb-0">
                                        <i class="fa-duotone fa-regular fa-inbox-in" style="--fa-primary-color: rgb(55, 221, 24); --fa-secondary-color: rgb(55, 221, 24);"></i>
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> 
                                        Ingresar stock
                                        </button>
                                    </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="alta" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="icantidad">Cantidad a ingresar</label>
                                                <input type="number" class="form-control" id="icantidad" required> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="icomentarios">Motivo</label>
                                                    <select class="form-control" id="icomentarios" required>
                                                    <option value="0">Selecciona el motivo</option>';

                                            foreach($rows5 as $row){
                                                $response=$response.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                            }
                                        $response=$response.'</select>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-success" id="saveProspectBtn" onclick="ingresoArticulo('.$campus.','.$articulo.')">Ingresar</button>
                                    </div>
                                    </div>
                                </div>';
                                if($rows1[0]["existencia"]!=0){
                                    $response=$response.'<div class="card">
                                    <div class="card-header" id="requerir">
                                    <h5 class="mb-0">
                                    <i class="fa-duotone fa-regular fa-inbox-out" style="--fa-primary-color: rgb(221, 28, 24); --fa-secondary-color: rgb(221, 28, 24);"></i>
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Egreso material
                                        </button>
                                    </h5>
                                    </div>
                                        <div id="collapseTwo" class="collapse" aria-labelledby="requerir" data-parent="#accordion">
                                                <div class="card-body">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label for="ecantidad">Cantidad a requerir</label>
                                                                    <input type="number" class="form-control" id="ecantidad" required max="'.$rows1[0]["stock"].'"> 
                                                                </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                    <label for="usuariofinal">Usuario a quien se entregará:</label>
                                                                    <select class="form-control" id="usuariofinal" required>
                                                                    <option value="0">Seleccionar usuario</option>';
                                                                        foreach($rows3 as $row){
                                                                            $response=$response.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                                                        }
                                                                    $response=$response.'</select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                    <label for="ecomentarios">Motivo:</label>
                                                                    <select class="form-control" id="ecomentarios" required>
                                                                        <option value="0">Seleccionar motivo</option>';
                                                                        foreach($rows4 as $row){
                                                                            $response=$response.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                                                        }
                                                                    $response=$response.'</select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-success" id="saveProspectBtn" onclick="egresoArticulo('.$campus.','.$articulo.')">Continuar</button>
                                                            
                                                            </div>
                                                </div>
                                        </div>
                                </div>';
                                }
                                $response=$response.'
                                <!--- <div class="card">
                                    <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Transferir
                                        </button>
                                    </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                    <div class="card-body">
                                     <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                            <label for="corigen">Transferir desde: </label>
                                            <select class="form-control" id="corigen" onchange="stockCampus('.$articulo.')" required>';
                                                foreach($rows2 as $row){
                                                    $response=$response.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                                }
                                            $response=$response.' <option value="8">CORPORATIVO</option></select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tcantidad">Stock actual</label>
                                                <input type="number" class="form-control" id="tcantidad" required min="1" max="'.$rows1[0]["stock"].'"> 
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                            <label for="creceptor">Campus receptor: </label>
                                            <select class="form-control" id="creceptor" required>';

                                            foreach($rows2 as $row){
                                                $response=$response.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                            }
                                        $response=$response.'
                                        <option value="8">CORPORATIVO</option></select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="tcomentarios">Comentarios</label>
                                                <textarea class="form-control" id="tcomentarios" rows="3" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-success" id="saveProspectBtn" onclick="transferenciaArticulo('.$articulo.')">Continuar</button>

                                    </div>
                                    </div>
                                </div>  -->
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>';
                        echo $response;
    break;


    case 30:
            $articulo=$_POST["articulo"];

            if($sesion[2]==1){
            $campus="sanjuan";
            }else if($sesion[2]==2){
            $campus="aculco";
            }else if($sesion[2]==3){
            $campus="tecamac";
            }else if($sesion[2]==4){
            $campus="atlacomulco";
            }else if($sesion[2]==5){
            $campus="tepeji";
            }else if($sesion[2]==6){
            $campus="nopala";
            }else if($sesion[2]==7){
            $campus="enlinea";
            }else if($sesion[2]==13){
            $campus="sanjuan5";
            }
             $stmt = $dbconn->prepare("SELECT id,area,codigo,nombre,medida,if(SUBSTRING((".$campus ."), -1,1)!=0,(".$campus ."),REPLACE((".$campus ."),'.0','')) AS total,foto FROM inventario where id='".$articulo."' order by id asc");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response=' <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">ARTICULO: '.$rows[0]["nombre"].'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Parte superior -->
                                        <div class="row">
                                        <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" disabled  value="'.$rows[0]["nombre"].'"> 
                                        </div>
                                        </div>

                                        <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="existencias">Existencias</label>
                                            <input type="text" class="form-control" id="existencias" disabled  value="'.$rows[0]["total"].'"> 
                                        </div>
                                        </div>
                                        </div>

                            <div class="row">
                                <!-- Columna izquierda para la imagen -->
                                <div class="col-md-12">
                                        <div class="row mt-5 p-5  justify-content-evenly">';
                                                if($rows[0]["foto"]!=""){
                                                $response = $response. '<img <img src="img/categorias/inventario/'.$rows[0]["foto"].'?v='.(@filemtime(__DIR__."/../img/categorias/inventario/".$rows[0]["foto"]) ?: time()).'" alt="Imagen" class="image-container rounded mx-auto d-block w-50" >';
                                                }else{
                                                    $response = $response. '<img src="img/undraw_profile.svg" alt="Imagen" class="image-container rounded mx-auto d-block w-50" >';
                                                }
                                                $response = $response. '
                                        </div>
                                </div>
                            </div>
                            </div>


                            <div class="row p-5">
                                <!-- Columna izquierda para la imagen -->
                                <div class="col-md-4">

                                <div class="form-group">
                                            <label for="existencias">ingresos</label>
                                            <input type="text" class="form-control" id="existencias" disabled  value="'.$rows[0]["total"].'"> 
                                        </div>
                                </div>

                                <div class="col-md-4">
                                <div class="form-group">
                                            <label for="existencias">Egresos</label>
                                            <input type="text" class="form-control" id="existencias" disabled  value="'.$rows[0]["total"].'"> 
                                        </div>
                                </div>

                                <div class="col-md-4">
                                <div class="form-group">
                                            <label for="existencias">Transferencias</label>
                                            <input type="text" class="form-control" id="existencias" disabled  value="'.$rows[0]["total"].'"> 
                                        </div>
                                </div>
                            </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>';
                        echo $response;
        break;


    }