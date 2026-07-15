<?php
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', 1);

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
                            <label class="custom-control-label" for="p14">Reuniones</label>
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

        $stmt = $dbconn->prepare("SELECT * FROM inventario_indices where status=1");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $dbconn->prepare("SELECT * FROM inventario_medidas where status=1");
        $stmt2->execute();
        $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Agregar Articulo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <!-- Formulario de Prospecto -->
                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                        <!-- Primera Fila: Nombre, Apellido Paterno, Apellido Materno -->
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
                            <!-- Segunda Fila: Nivel (select), Modalidad (select), Ciclo -->
                            <div class="col-md-8">
                                <div class="form-group">
                                <label for="categoria">Categoria</label>
                                <select class="form-control" id="categoria" required>';

                                foreach($rows as $row){
                                    $response=$response.'<option value="'.$row["id"].'">'.$row["categoria"].'</option>';
                                }
                               $response=$response.' </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="medida">Unidad de medida</label>
                                <select class="form-control" id="medida" required>';

                                foreach($rows2 as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["medida"].'</option>';
                                } 
                               $response=$response.' </select>
                                        </div>
                                    </div>
                                </div>
                               
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="saveProspectBtn" onclick="guardarArticulo()">Agregar articulo</button>
                        </div>';
                        echo $response;
        break;

        case 18:
            $nombre=strtoupper($_POST["nombre"]);
            $marca=strtoupper($_POST["marca"]);
            $categoria=strtoupper($_POST["categoria"]);
            $medida=strtoupper($_POST["medida"]);

            $stmt = $dbconn->prepare("SELECT CONCAT(codigo,'-',conteo+1) AS codigo FROM inventario_indices where id='".$categoria."'");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            try{
               $query = "INSERT inventario VALUES ('','".$categoria."','".$rows[0]['codigo']."','".$nombre."','".$medida."','".$marca."','0','0','0','0','0','0','0','0','1','')";
				$consulta = $dbconn->prepare($query);
				$consulta->execute();   
                
                $query = "UPDATE inventario_indices SET conteo=conteo+1 WHERE id='".$categoria."'";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();

                
            $stmtm = $dbconn->prepare("SELECT MAX(id) as maximo FROM inventario");
            $stmtm->execute();
            $rowsm = $stmtm->fetchAll(PDO::FETCH_ASSOC);


                $query = "INSERT into inventario_movimientos (articulo,usuario,tipo,campusIngreso,campusEgreso,cantidad,comentario,hora)
                values ('".$rowsm[0]["maximo"]."','".$sesion[0]."','0','0','0','0','ALTA DE ARTICULO',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();

                echo $response = 1;
            } catch (Error $e) {
                //file_put_contents('../include/log.txt', print_r("error ---------------".$e, true), FILE_APPEND);
                echo $response = 0;
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
                                                            $response = $response. '<img <img src="img/categorias/inventario/'.$rows[0]["foto"].'"alt="Imagen" class="image-container rounded mx-auto d-block w-50">';
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
                                                <input type="number" class="form-control" id="icantidad" required> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="icomentarios">Comentarios</label>
                                                    <textarea class="form-control" id="icomentarios" rows="3" required></textarea>
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
                                                                    <input type="number" class="form-control" id="ecantidad" required> 
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label for="ecomentarios">Comentarios</label>
                                                                        <textarea class="form-control" id="ecomentarios" rows="3" required></textarea>
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
                                                <input type="number" class="form-control" id="tcantidad" required value="'.$rows[0]["sanjuan"].'"> 
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
            $response=$row[0]["tepeji"];
        }else if($campus== 5){
            $response=$row[0]["atlacomulco"];
        }else if($campus== 6){
            $response=$row[0]["nopala"];
        }else if($campus== 7){
            $response=$row[0]["enlinea"];
        }else if($campus== 8){
            $response=$row[0]["corporativo"];
        }
        echo $response;
        break;


        case 21:
        $articulo=$_POST["articulo"];
        $campus=$_POST["campus"];
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
            $scampus=$row[0]["tepeji"];
            $ncampus="tepeji";
        }else if($campus== 5){
            $scampus=$row[0]["atlacomulco"];
            $ncampus="atlacomulco";
        }else if($campus== 6){
            $scampus=$row[0]["nopala"];
            $ncampus="nopala";
        }else if($campus== 7){
            $scampus=$row[0]["enlinea"];
            $ncampus="enlinea";
        }else if($campus== 8){
            $scampus=$row[0]["corporativo"];
            $ncampus="corporativo";
        }
        
        try{
             $query = "UPDATE inventario SET ".$ncampus."=".$scampus."+".$cantidad." WHERE id='".$articulo."'";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();
                
                //SIGNIFICADO DE TIPO DE MOVIMIENTO
                //1 = INGRESO
                //2 = EGRESO
                //3 = TRANFERENCIA

                $query = "INSERT into inventario_movimientos (articulo,usuario,tipo,campusIngreso,campusEgreso,cantidad,comentario,hora)
                values ('".$articulo."','".$sesion[0]."','1','".$campus."','0','".$cantidad."','".$comentarios."',NOW())";
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
            $scampus=$row[0]["tepeji"];
            $ncampus="tepeji";
        }else if($campus== 5){
            $scampus=$row[0]["atlacomulco"];
            $ncampus="atlacomulco";
        }else if($campus== 6){
            $scampus=$row[0]["nopala"];
            $ncampus="nopala";
        }else if($campus== 7){
            $scampus=$row[0]["enlinea"];
            $ncampus="enlinea";
        }else if($campus== 8){
            $scampus=$row[0]["corporativo"];
            $ncampus="corporativo";
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

                $query = "INSERT into inventario_movimientos (articulo,usuario,tipo,campusIngreso,campusEgreso,cantidad,comentario,hora)
                values ('".$articulo."','".$sesion[0]."','2','0','".$campus."','".$cantidad."','".$comentarios."',NOW())";
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
            $scampus=$row[0]["tepeji"];
            $ncampus="tepeji";
        }else if($campus== 5){
            $scampus=$row[0]["atlacomulco"];
            $ncampus="atlacomulco";
        }else if($campus== 6){
            $scampus=$row[0]["nopala"];
            $ncampus="nopala";
        }else if($campus== 7){
            $scampus=$row[0]["enlinea"];
            $ncampus="enlinea";
        }else if($campus== 8){
            $scampus=$row[0]["corporativo"];
            $ncampus="corporativo";
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
            $rscampus=$row[0]["tepeji"];
            $rncampus="tepeji";
        }else if($creceptor== 5){
            $rscampus=$row[0]["atlacomulco"];
            $rncampus="atlacomulco";
        }else if($creceptor== 6){
            $rscampus=$row[0]["nopala"];
            $rncampus="nopala";
        }else if($creceptor== 7){
            $rscampus=$row[0]["enlinea"];
            $rncampus="enlinea";
        }else if($creceptor== 8){
            $rscampus=$row[0]["corporativo"];
            $rncampus="corporativo";
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

                $query = "INSERT into inventario_movimientos (articulo,usuario,tipo,campusIngreso,campusEgreso,cantidad,comentario,hora)
                values ('".$articulo."','".$sesion[0]."','3','".$creceptor."','".$campus."','".$cantidad."','".$comentarios."',NOW())";
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
             $stmt = $dbconn->prepare("SELECT id,area,codigo,nombre,medida,if(SUBSTRING((sanjuan + aculco + tecamac + tepeji + atlacomulco + nopala + enlinea + corporativo), -1,1)!=0,(sanjuan + aculco + tecamac + tepeji + atlacomulco + nopala + enlinea + corporativo),REPLACE((sanjuan + aculco + tecamac + tepeji + atlacomulco + nopala + enlinea + corporativo),'.0','')) AS total,foto FROM inventario where id='".$articulo."' order by id asc");
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
                                                $response = $response. '<img <img src="img/categorias/inventario/'.$rows[0]["foto"].'" alt="Imagen" class="image-container rounded mx-auto d-block w-50" onclick="articuloFoto('.$articulo.')">';
                                                }else{
                                                    $response = $response. '<img src="img/undraw_profile.svg" alt="Imagen" class="image-container rounded mx-auto d-block w-50" onclick="articuloFoto('.$articulo.')">';
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

                    $query = "INSERT into inventario_movimientos (articulo,usuario,tipo,campusIngreso,campusEgreso,cantidad,comentario,hora)
                values ('".$articulo."','".$sesion[0]."','5','0','0','0','BAJA DE ARTICULO',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();
                    $response=1;
            }catch(Error $e){
                    $response=0;

            }
           
            echo $response;

        break;

        case 27:
        
        $articulo=$_POST["articulo"];
        $stmt = $dbconn->prepare("SELECT * FROM inventario where id='".$articulo."'");
        $stmt->execute();
        $articulor = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $dbconn->prepare("SELECT * FROM inventario_indices where status=1");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $dbconn->prepare("SELECT * FROM inventario_medidas where status=1");
        $stmt2->execute();
        $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Modificacion de articulo: '.$articulor[0]["nombre"].'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <!-- Formulario de Prospecto -->
                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                        <!-- Primera Fila: Nombre, Apellido Paterno, Apellido Materno -->
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" required value="'.$articulor[0]["nombre"].'"> 
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="marca">Marca</label>
                                <input type="text" class="form-control" id="marca" required value="'.$articulor[0]["marca"].'"> 
                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <!-- Segunda Fila: Nivel (select), Modalidad (select), Ciclo -->
                            <div class="col-md-8">
                                <div class="form-group">
                                <label for="categoria">Categoria</label>
                                <select class="form-control" id="categoria" required>';

                                foreach($rows as $row){
                                    if( $articulor[0]["area"] == $row['id']){
                                    $response=$response.'<option value="'.$row["id"].'" selected="selected" >'.$row["categoria"].'</option>';
                                    }else{
                                    $response=$response.'<option value="'.$row["id"].'">'.$row["categoria"].'</option>';
                                    }
                                }
                               $response=$response.' </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="medida">Unidad de medida</label>
                                <select class="form-control" id="medida" required>';

                                foreach($rows2 as $row){
                                    if($row['id']== $articulor[0]['medida']){
                                    $response=$response.' <option value="'.$row["id"].'" selected="selected">'.$row["medida"].'</option>';
                                    }
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["medida"].'</option>';
                                } 
                               $response=$response.' </select>
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
                            <button type="button" class="btn btn-secondary" onclick="articulo('.$articulo.')" >Cancelar</button>
                            <button type="button" class="btn btn-success" id="saveProspectBtn" onclick="guardarActualizacionArticulo('.$articulor[0]["id"].')">Guardar cambios</button>
                        </div>';
                        echo $response;
        break;
        
        case 28:
            $articulo=strtoupper($_POST["articulo"]);
            $nombre=strtoupper($_POST["nombre"]);
            $marca=strtoupper($_POST["marca"]);
            $categoria=strtoupper($_POST["categoria"]);
            $medida=strtoupper($_POST["medida"]);
            $comentarios=strtoupper($_POST["comentarios"]);

            try{
               $query = "UPDATE inventario SET nombre='".$nombre."',marca='".$marca."',area='".$categoria."',medida='".$medida."' WHERE id='".$articulo."'";
			    $consulta = $dbconn->prepare($query);
				$consulta->execute();   
                

                $query = "INSERT into inventario_movimientos (articulo,usuario,tipo,campusIngreso,campusEgreso,cantidad,comentario,hora)  values ('".$articulo."','".$sesion[0]."','4','0','0','0','MODIFICACION DE ARTICULO:".$comentarios."',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();

                $response = 1;
            } catch (Error $e) {
                $response = $e;
            }

            echo $response;
 
        break;

        
            
    case 29:
        $cp = $_POST["cp"];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,  'https://api.tau.com.mx/dipomex/v1/codigo_postal?cp=' . $cp);
        /** Ingresamos la url de la api o servicio a consumir */
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        /**Permitimos recibir respuesta*/
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        $headers = array(
            "APIKEY: ec632679f6c95d76f1f11531ee86d5d8c2f9b818",
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml"
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, false);
        $response = curl_exec($curl);
        $arr = json_decode($response, TRUE);



        if ($arr['message'] == "Procesamiento correcto.") {

            foreach ($arr as $row) {
                $arrConnections = $row["colonias"]; //Es un array
            }

            $municipios;
            foreach ($arrConnections as $row) {
                if(strlen($row)==0){

                }else{
                $municipios = $municipios . "," . strtoupper($row); //Aquí podemos usar los valores como variables o usar echo
                }
            }

            echo ($municipios . "|" . $arr['codigo_postal']['municipio'] . "|" . $arr['codigo_postal']['estado']);
        } else {
            echo "0";
        }

        break;

    case 30:

         $id=$_POST["id"];

            try{
               $query = "UPDATE usuarios SET status='0' WHERE id='".$id."'";
			    $consulta = $dbconn->prepare($query);
				$consulta->execute();   
                

                $query = "INSERT into log (usuario,descripcion,hora)  values ('".$sesion[0]."','BAJA DEL USUARIO CON EL ID: ".$id."',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();

                $response = 1;
            } catch (Error $e) {
                $response = $e;
            }

            echo $response;

        break;

         case 31:

         $id=$_POST["id"];

            try{
               $query = "UPDATE usuarios SET status='1' WHERE id='".$id."'";
			    $consulta = $dbconn->prepare($query);
				$consulta->execute();   
                

                $query = "INSERT into log (usuario,descripcion,hora)  values ('".$sesion[0]."','REACTIVACIÓN DEL USUARIO CON EL ID: ".$id."',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();

                $response = 1;
            } catch (Error $e) {
                $response = $e;
            }

            echo $response;

        break;

        
        case 32:

            try { 
                    $stmt = $dbconn->prepare("SELECT * from tipo_duracion");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    echo $e;
                }

        $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Agregar ciclo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                        <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 20rem;"
                                            src="img/categorias/svg/ciclos.svg" alt="...">
                                    </div>
                                    <p>Al agregar un ciclo debes definir si lo colcocaras como cuatrimestral, semestral, etc. posteriormente selecciona la fecha de inicio del ciclo, este será el primer valor el cual se calculará para poder determinar de manera automatica la fecha de fin de ese ciclo y la nomenclatura de manera automatica asi como el dia final.</p>
                        </div>

                        <!-- Formulario de Prospecto -->
                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="finicio">tipo de duración</label>
                                            <select class="form-control" id="tduracion" onchange="iniciociclo()" >
                                            <option value="0">Seleccionar duración</option>';
                                         foreach ($rows as $row) {
                                          $response=$response.'<option value="'.$row["id"] .'">'.$row["nombre"] .'</option>';
                                            } 
                                   $response=$response.'

                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="finicio">Fecha de inicio</label>
                                                <input type="month" class="form-control" id="finicio" required onchange="finciclo()" disabled> 
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="ffinal">Fecha de termino</label>
                                                <input type="month" class="form-control" id="ffinal" required disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="nomen">Nomenclatura</label>
                                                <input type="text" class="form-control" id="nomen" required disabled>
                                            </div>
                                        </div>
                             </div>
                        
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="guardarciclov" onclick="guardarciclo()" value="">Agregar ciclo</button>
                        </div>';
                        echo $response;
        break;    


        case 33:
            
            $tduracion=$_POST["tduracion"];
            $finicio=$_POST["finicio"];
            $ffinal=$_POST["ffinal"];
            $nomen=$_POST["nomen"];

            try{
                $query = "INSERT ciclos VALUES ('','".$finicio."','".$ffinal."','".$nomen."','".$tduracion."')";
                    $exc_query = $dbconn->prepare($query);
                    $exc_query->execute();
                    echo $response = 1;
            } catch (Error $e) {
                
                //file_put_contents('../include/log.txt', print_r("error ---------------".$e, true), FILE_APPEND);
                echo $response = 0;
            }
 
        break;

        case 34:

            
            try { 
                    $stmt = $dbconn->prepare("SELECT * from niveles");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $stmt2 = $dbconn->prepare("SELECT * from carreras_clasificacion");
                    $stmt2->execute();
                    $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                    $stmt3 = $dbconn->prepare("SELECT * from tipo_duracion");
                    $stmt3->execute();
                    $rows3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    echo $e;
                }

        $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Agregar carrera</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                        <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 20rem;"
                                            src="img/categorias/svg/notes.svg" alt="...">
                                    </div>
                                    <p>Para agregar una carrea, sólo debes colocar el nombre de la misma y el Rvoe, esto servirá para que al asignarla a un plantel pueda ser promocionada</p>
                        </div>

                        <!-- Formulario de Prospecto -->
                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <label for="finicio">Nivel</label>
                                            <select class="form-control" id="nivel" onchange="vnivel(this.value)" >
                                            <option value="0">Seleccionar nivel</option>';
                                         foreach ($rows as $row) {
                                          $response=$response.'<option value="'.$row["id"] .'">'.$row["nombre"] .'</option>';
                                            } 
                                   $response=$response.'

                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="carrera">Carrera</label>
                                                <input type="text" class="form-control" id="carrera" required style="text-transform: uppercase">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="rvoe">RVOE</label>
                                                <input type="text" class="form-control" id="rvoe" required style="text-transform: uppercase">
                                            </div>
                                        </div>
                                </div>
                                <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <label for="finicio">Clasificación</label>
                                            <select class="form-control" id="clasif">
                                            <option value="0">Seleccionar clasificación</option>';
                                         foreach ($rows2 as $row) {
                                          $response=$response.'<option value="'.$row["id"] .'">'.$row["nombre"] .'</option>';
                                            } 
                                   $response=$response.'

                                            </select>
                                            </div>
                                        </div>

                                         <div class="col-md-4">
                                            <div class="form-group">
                                            <label for="t_duracion">Tipo de duración</label>
                                            <select class="form-control" id="t_duracion">
                                            <option value="0">Seleccionar tipo de duración</option>';
                                         foreach ($rows3 as $row) {
                                          $response=$response.'<option value="'.$row["id"] .'">'.$row["nombre"] .'</option>';
                                            } 
                                   $response=$response.'

                                            </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ciclos">Ciclos</label>
                                                <input type="text" class="form-control" id="ciclos" required>
                                            </div>
                                        </div>
                                </div>
                        
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="guardarcarrera" onclick="cuardarcarrera()" value="">Agregar carrera</button>
                        </div>';
                        echo $response;

            break;

        case 35:

           
            $inicio=$_POST["inicio"];
            $tduracion=strtoupper($_POST["tduracion"]);

             $meses = [
            1 => "Ene",
            2 => "Feb",
            3 => "Mar",
            4 => "Abr",
            5 => "May",
            6 => "Jun",
            7 => "Jul",
            8 => "Ago",
            9 => "Sep",
            10 => "Oct",
            11 => "Nov",
            12 => "Dic"
            ];



            try {
                    $stmt = $dbconn->prepare("SELECT valor-1 as valor from tipo_duracion where id='".$tduracion."'");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


            $suma="+".$rows[0]["valor"]." months";


            $mesresultado = date("Y-m-d", strtotime($suma, strtotime($inicio)));

            $date = new DateTime($mesresultado); // Cambia la fecha según sea necesario
            $date->modify('last day of this month');
            $response=  $date->format('Y-m-d');
                } catch (Exception $e) {
                    $response= $e;
                }

           
            $fin= $meses[date("n",strtotime($inicio))].date("y",strtotime($inicio));
            $ffinal= $meses[date("n",strtotime($response))].date("y",strtotime($mesresultado));

            echo $response."|".$fin."-".$ffinal;

            break;

            case 36:

            $nivel=$_POST["nivel"];
            $carrera=$_POST["carrera"];
            $rvoe=$_POST["rvoe"];
            $ciclos=$_POST["ciclos"];
            $clasif=$_POST["clasif"];
            $t_duracion=$_POST["t_duracion"];

             $stmt = $dbconn->prepare("SELECT * from niveles where id='".$nivel."'");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

             try{
                $query = "INSERT carreras VALUES ('','".$rows[0]["nombre"]." EN ".$carrera."','".$rvoe."','".$nivel."','".$t_duracion."','".$ciclos."','".$clasif."')";
                    $exc_query = $dbconn->prepare($query);
                    $exc_query->execute();
                    echo $response = 1;
            } catch (Error $e) {
                
                //file_put_contents('../include/log.txt', print_r("error ---------------".$e, true), FILE_APPEND);
                echo $response = 0;
            }


            break;

        case 37:

            $carrera=$_POST["carrera"];

                    try{ 
                    $stmtc = $dbconn->prepare("SELECT * from carreras where id='".$carrera."'");
                    $stmtc->execute();
                    $rowsc = $stmtc->fetchAll(PDO::FETCH_ASSOC);

                    $stmt = $dbconn->prepare("SELECT * from planteles WHERE id!=8");

                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }catch (Exception $e) {
                        echo $e;
                    }

                    $response='
                    <div class="modal-header">
                            <h5 class="modal-title text-primary" id="addProspectModalLabel">'.$rowsc[0]["nombre"].' - '.$rowsc[0]["rvoe"].'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 20rem;"
                                                    src="img/categorias/svg/notes.svg" alt="...">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="m-3">Para agregar una carrea, sólo debes colocar el nombre de la misma y el Rvoe, esto servirá para que al asignarla a un plantel pueda ser promocionada</p>
                                        </div>
                                    </div>

                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Campus donde se imparte</h1>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="carreraImpartida('.$carrera.')">
                            <i class="fas fa-download fa-sm text-white-100"></i>        Asignar carrera a un campus
                        </a>
                        </div>

                         <div class="card-body"> 
                         
                         <div id="accordion">';

                        foreach($rows as $row){                     

                            $response=$response.'
                                  <div class="card">
                                    <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#kjh'.$row["id"].'-'.$carrera.'" aria-expanded="false" aria-controls="kjh'.$row["id"].'-'.$carrera.'"  onclick="datoscampuscarrera('.$row["id"].",".$carrera.')">
                                        '.$row["nombre"].'
                                        </button>
                                        
                                    </h5>                                    
                                    </div>

                                    <div id="kjh'.$row["id"].'-'.$carrera.'" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                   
                                    </div>
                                </div>
                            ';



                        }


                        
                        $response=$response.'
                        
                        </div>

                        </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>';
                        echo $response;



        break;


        case 38:
            $campus=$_POST["campus"];
            $carrera=$_POST["carrera"];
            
                try { 
                    $stmtc = $dbconn->prepare("SELECT * from carreras where id='".$carrera."'");
                    $stmtc->execute();
                    $rowsc = $stmtc->fetchAll(PDO::FETCH_ASSOC);

                    $stmt = $dbconn->prepare("SELECT * from modalidades");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    
                $response='
                        <div class="card-body">

                        <div class="container my-auto">';
                        foreach($rows as $row){

                        $response=$response.'       
                        
                        <div class="row">
                        <div class="custom-control custom-switch">';

                                    $stmt = $dbconn->prepare("SELECT * from carreras_plantel where modalidad='".$row["id"]."' and plantel='".$campus."' and carrera='".$carrera."'");
                                    $stmt->execute();

                                     if($stmt->rowCount()!=0){
                                         $rowi = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if($rowi[0]["status"]==1){
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="sw-'.$carrera.'-'.$campus.'-'.$row["id"].'" name="chk-'.$carrera.'-'.$campus.'" checked>';
                                        }else{
                                            $response=$response.'<input type="checkbox" class="custom-control-input" id="sw-'.$carrera.'-'.$campus.'-'.$row["id"].'" name="chk-'.$carrera.'-'.$campus.'">';
                                        }

                                     }else{
 
                                        $response=$response.'<input type="checkbox" class="custom-control-input" id="sw-'.$carrera.'-'.$campus.'-'.$row["id"].'" name="chk-'.$carrera.'-'.$campus.'">';
                                     }
                                   
                    
                                    
                               $response=$response.'
                        <label class="custom-control-label" for="sw-'.$carrera.'-'.$campus.'-'.$row["id"].'">'.$row["nombre"].'</label>
                        </div>
                        </div>';
                        }

                        $response=$response.'
                        </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="contarCheckboxes('.$carrera.','.$campus.','.$rowsc[0]["nivel"].','.$rowsc[0]["tipo_duracion"].','.$rowsc[0]["ciclos"].')">Guardar cambios</button>
                        </div>
                        
                        ';


                } catch (Exception $e) {
                    $response= $e;
                }

                        
                        echo $response;
        break;

        case 39:

            $nivel=$_POST["nivel"];
            $duracion=$_POST["duracion"];
            $ciclos=$_POST["ciclos"];
            $plantel=$_POST["plantel"];
            $carrera=$_POST["carrera"];
            $modalidad=explode(",",$_POST["datos"]);
            
                try {

                    foreach($modalidad as $element){

                        $datos=explode("-",$element);


                        $stmt = $dbconn->prepare("SELECT * from carreras_plantel where carrera='".$carrera."' and plantel='".$plantel."' and modalidad='".$datos[0]."'");
                        $stmt->execute();
                       

                            if($stmt->rowCount()!=0){
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $query = "UPDATE carreras_plantel SET status = '".$datos[1]."'  WHERE id = '".$rows[0]["id"]."'" ; 
                                $consulta = $dbconn->prepare($query);
                                $consulta->execute();
                                  $response= 1;

                            }else{

                                $query = "INSERT carreras_plantel VALUES ('','".$carrera."','".$nivel."','".$datos[0]."','".$plantel."','".$duracion."','".$ciclos."','".$datos[1]."')";
                                $exc_query = $dbconn->prepare($query);
                                $exc_query->execute();
                                                
                                  $response= 1;
                            }
                    }
                }catch(Error $e){
                    $response= $e;
                }

                        
                        echo $response;
        break;

        case 40:
            
        $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Agregar Nivel</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <!-- Formulario de Prospecto -->
                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" required> 
                                            </div>
                                        </div>
                             </div>
                        
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="saveProspectBtn" onclick="guardarNivel()">Agregar Nivel</button>
                        </div>';
                        echo $response;
            break;

            case 41:
                
            $nombre=strtoupper($_POST["nombre"]);

            try{
                $query = "INSERT niveles VALUES ('','".$nombre."','1')";
                    $exc_query = $dbconn->prepare($query);
                    $exc_query->execute();

                    $query = "INSERT into log (usuario,descripcion,hora)  values ('".$sesion[0]."','CREACIÓN DE NIVEL: ".$nombre."',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();
                    echo $response = 1;
            } catch (Error $e) {
                
                //file_put_contents('../include/log.txt', print_r("error ---------------".$e, true), FILE_APPEND);
                echo $response = 0;
            }
                break;

                
        case 42:

        $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Agregar Modalidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" required> 
                                            </div>
                                        </div>
                             </div>
                        
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="saveProspectBtn" onclick="guardarModalidad()">Agregar Modalidad</button>
                        </div>';
                        echo $response;
        break;

        case 43:
            $nombre=strtoupper($_POST["nombre"]);

            try{
                $query = "INSERT modalidades VALUES ('','".$nombre."','1')";
                    $exc_query = $dbconn->prepare($query);
                    $exc_query->execute();

                    $query = "INSERT into log (usuario,descripcion,hora)  values ('".$sesion[0]."','CREACIÓN DE MODALIDAD: ".$nombre."',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();
                    echo $response = 1;
            } catch (Error $e) {
                
                //file_put_contents('../include/log.txt', print_r("error ---------------".$e, true), FILE_APPEND);
                echo $response = 0;
            }
 
        break;
    }