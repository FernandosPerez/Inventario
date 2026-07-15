<?php
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', 1);
include("../include/conn.php");

session_start();
$sesion=explode("|",$_SESSION["usuario"]);

switch($_REQUEST['op']){ 

        case 1:

        $stmt = $dbconn->prepare("SELECT * FROM niveles");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt3 = $dbconn->prepare("SELECT * FROM modalidades where status=1");
        $stmt3->execute();
        $rows3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        $stmt4 = $dbconn->prepare("SELECT * FROM ciclos where fecha_termino>=CURDATE() ");
        $stmt4->execute();
        $rows4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
        
        //consulta de campus por parte de un corporativo
        if($sesion[2]==8){
            $stmt5 = $dbconn->prepare("SELECT id,nombre as campus FROM planteles where id!='".$sesion[2]."'");
        }else{
             $stmt5 = $dbconn->prepare("SELECT p.id as id,p.nombre as campus FROM usuarios u left join planteles p on p.id=u.campus where u.id='".$sesion[0]."' ");
        }
        
       
        $stmt5->execute();
        $rows5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);

        $stmt6 = $dbconn->prepare("SELECT * FROM canal_comunicacion");
        $stmt6->execute();
        $rows6 = $stmt6->fetchAll(PDO::FETCH_ASSOC);

        $stmt7 = $dbconn->prepare("SELECT * FROM medio_difusion");
        $stmt7->execute();
        $rows7 = $stmt7->fetchAll(PDO::FETCH_ASSOC);

            $response='
                    <div class="modal-header">
                            <h5 class="modal-title" id="addProspectModalLabel">Agregar prospecto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <!-- Formulario de Prospecto -->


                         <div class="mb-3 pb-3">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0" style="width: 0%" id="progress"></div>
                            </div>
                        </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 20rem;"
                                                    src="img/categorias/svg/prospect.svg" alt="...">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="m-3">Para agregar una carrera, sólo debes colocar el nombre de la misma y el Rvoe, esto servirá para que al asignarla a un plantel pueda ser promocionada</p>
                                        </div>
                                    </div>


                        <form id="prospectForm" autocomplete="off" >
                            <div class="row">
                        <!-- Primera Fila: Nombre, Apellido Paterno, Apellido Materno -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" required autocomplete="off"> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellidoPaterno">Apellido Paterno</label>
                                <input type="text" class="form-control" id="apellidoPaterno" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellidoMaterno">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellidoMaterno" required autocomplete="off">
                            </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Segunda Fila: Nivel (select), Modalidad (select), Ciclo -->
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="campus">Campus</label>
                                <select class="form-control" id="campus">';

                                foreach($rows5 as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["campus"].'</option>';
                                }  
                                $response=$response.' </select>
                                    </div>
                                </div>


                                 <div class="col-md-4">
                                <div class="form-group">
                                <label for="nivel">Nivel</label>
                                <select class="form-control" id="nivel" required onchange="listas1()">
                                <option value="0">SELECCIONAR NIVEL</option>';

                                foreach($rows as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                }  
                                $response=$response.' </select>
                                    </div>
                                </div>

                        
                            <div class="col-md-4">
                            <div class="form-group">
                                <label for="carrera">Carrera</label>
                                <select class="form-control" id="carrera" required  onchange="listas2()">
                                </select>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modalidad">Modalidad</label>
                                <select class="form-control" id="modalidad" required >
                                </select>
                                        </div>
                                    </div>

                                     <div class="col-md-6">
                                    <div class="form-group">
                                <label for="ciclo">Ciclo</label>
                                <select class="form-control" id="ciclo" required>
                                <option value="0">SELECCIONAR CICLO</option>';

                                foreach($rows4 as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nomenclatura"].'</option>';
                                }      
                               $response=$response.' </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <!-- Tercera Fila: Teléfono, Correo Electrónico -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="canal">Canal de comunicación</label>
                                <select class="form-control" id="canal" required>
                                <option value="0">SELECCIONAR...</option>';

                                foreach($rows6 as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                }      
                               $response=$response.' </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                         <label for="difusion">Medio de difusión</label>
                                <select class="form-control" id="difusion" required>
                                <option value="0">SELECCIONAR...</option>';

                                foreach($rows7 as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                                }      
                               $response=$response.' </select>
                                        </div>
                                    </div>
                                </div>

                                 <div class="row">
                                    <!-- Tercera Fila: Teléfono, Correo Electrónico -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono</label>
                                            <input type="text" class="form-control" id="telefono" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10" required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" required autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                        </div>


                    <div class="row m-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="1">Código Postal</label>
                            <div class="input-group ">
                                <input type="text" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="5" id="1" autocomplete="off">
                                <span class="input-group-text" id="basic-addon2" style="background-color: #2e59d9;" onclick="codigoPostal()"><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="2">Calle</label>
                            <input type="text" class="form-control" id="2" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="6">Numero ext.</label>
                            <input type="text" class="form-control" id="6" autocomplete="off" data-toggle="tooltip"  data-placement="top" title="Si no cuenta con numero, ingresa 0" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="5">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="7">Numero Int.</label>
                            <input type="text" class="form-control" id="7" autocomplete="off" data-toggle="tooltip" data-placement="top" title="Si no cuenta con numero, ingresa 0" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="5">
                        </div>
                    </div>
                </div>


                 <div class="row m-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="3">Localidad</label>
                            <select class="form-control" id="3">
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
                    </form>
                </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="saveProspectBtn" onclick="guardarProspecto()">Agregar prospecto</button>
                        </div>';
                        echo $response;
        break;

        case 2:
            
            $nombre =$_POST["nombre"];
            $apellidoPaterno =$_POST["apellidoPaterno"];
            $apellidoMaterno =$_POST["apellidoMaterno"];
            $campus =$_POST["campus"];
            $nivel =$_POST["nivel"];
            $carrera =$_POST["carrera"];
            $modalidad =$_POST["modalidad"];
            $ciclo =$_POST["ciclo"];
            $canal =$_POST["canal"];
            $difusion =$_POST["difusion"];
            $telefono =$_POST["telefono"];
            $correo =$_POST["correo"];
            $cp =$_POST["cp"];
            $calle =$_POST["calle"];
            $localidad=str_replace("-"," ",substr($_POST["localidad"],0,-1));
            $municipio =$_POST["municipio"];
            $estado =$_POST["estado"];
            $numext =$_POST["numext"];
            $numint =$_POST["numint"];


            //nivel carrera y modalidad aparte
            

            try{
    $sql = "INSERT into alumnos (nombres, apellidoPaterno, apellidoMaterno,campus,ciclo,prospecto_creacion,canal,difusion,telefono,correo,cp,calle,localidad,municipio,estado,numext,numint,usuario_creacion,status) VALUES
                ('".$nombre."','".$apellidoPaterno."','".$apellidoMaterno."','".$campus."','".$ciclo."',NOW(),'".$canal."','".$difusion."','".$telefono."','".$correo."','".$cp."','".$calle."','".$localidad."','".$municipio."','".$estado."','".$numext."','".$numint."','".$sesion[0]."','1')";
    $dbconn->exec($sql);
    $id = $dbconn->lastInsertId();

                            $query = "INSERT into log (usuario,descripcion,hora)  values ('".$sesion[0]."','CREACIÓN DE PROSPECTO CON EL ID: ".$id."',NOW())";
                $exc_query = $dbconn->prepare($query);
                $exc_query->execute();


    $sql = "INSERT into alumno_interes (alumno,nivel,carrera,modalidad,activo) VALUES
                ('".$id."','".$nivel."','".$carrera."','".$modalidad."','1')";
    $dbconn->exec($sql);

                    $response = 1;
            } catch (Error $e) {
                $response = 0;
            }

            echo $response;
            break;


            
    case 3:
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
                $municipios = $municipios . "," . strtoupper($row); //Aquí podemos usar los valores como variables o usar echo
            }

            echo ($municipios . "|" . $arr['codigo_postal']['municipio'] . "|" . $arr['codigo_postal']['estado']);
        } else {
            echo "0";
        }

        break;

        case 4:
            $nivel=$_POST["nivel"];
              $campus=$_POST["campus"];

             $stmt2 = $dbconn->prepare("SELECT c.id,c.nombre,p.plantel FROM carreras_plantel p  left join carreras c on c.id=p.carrera  where p.plantel='".$campus."' and p.nivel='".$nivel."' group by c.id");
        $stmt2->execute();
        $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        
            $response='<option value="0">SELECCIONAR CARRERAS</option>';

            foreach($rows2 as $row){
                $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';
            }

            echo $response;
        
        break;

        case 5:
            $nivel=$_POST["nivel"];
            $carrera=$_POST["carrera"];
            $campus=$_POST["campus"];


        $stmt2 = $dbconn->prepare("SELECT c.id,c.nombre,p.plantel FROM carreras_plantel p  left join modalidades c on c.id=p.modalidad
        WHERE p.plantel='".$campus."' AND p.carrera='".$carrera."' AND p.status=1 order by c.id");
        $stmt2->execute();
        $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        
            $response='<option value="0">SELECCIONAR MODALIDAD</option>';

            foreach($rows2 as $row){
                $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';
            }

            echo $response;

        break;

        case 6:
            $id=$_POST["id"];

        


        $stmt = $dbconn->prepare("SELECT * FROM niveles");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt1 = $dbconn->prepare("SELECT * FROM alumno_interes where alumno='".$id."'");
        $stmt1->execute();
        $rows1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $dbconn->prepare("SELECT * FROM alumnos where id='".$id."'");
        $stmt2->execute();
        $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $stmt3 = $dbconn->prepare("SELECT * FROM modalidades where status=1");
        $stmt3->execute();
        $rows3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        $stmt4 = $dbconn->prepare("SELECT * FROM ciclos where fecha_termino>=CURDATE() ");
        $stmt4->execute();
        $rows4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
        
        if($sesion[2]==8){
$stmt5 = $dbconn->prepare("SELECT id,nombre as campus FROM planteles where id!='".$sesion[2]."'");
        }else{
$stmt5 = $dbconn->prepare("SELECT  p.id as id,p.nombre as campus FROM usuarios u left join planteles p on p.id=u.campus where u.id='".$sesion[0]."' ");
        }
        
        $stmt5->execute();
        $rows5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);

        $stmt6 = $dbconn->prepare("SELECT * FROM canal_comunicacion");
        $stmt6->execute();
        $rows6 = $stmt6->fetchAll(PDO::FETCH_ASSOC);

        $stmt7 = $dbconn->prepare("SELECT * FROM medio_difusion");
        $stmt7->execute();
        $rows7 = $stmt7->fetchAll(PDO::FETCH_ASSOC);

        $stmt8 = $dbconn->prepare("SELECT c.id,c.nombre,p.plantel FROM carreras_plantel p  left join carreras c on c.id=p.carrera  where p.plantel='".$sesion[2]."' and p.nivel='".$rows1[0]["nivel"]."' group by c.id");
        $stmt8->execute();
        $rows8 = $stmt8->fetchAll(PDO::FETCH_ASSOC);

        $stmt9 = $dbconn->prepare("SELECT c.id,c.nombre,p.plantel FROM carreras_plantel p  left join modalidades c on c.id=p.modalidad
        WHERE p.plantel='".$sesion[2]."' AND p.carrera='".$rows1[0]["carrera"]."' AND p.status=1 order by c.id");
        $stmt9->execute();
        $rows9 = $stmt9->fetchAll(PDO::FETCH_ASSOC);

        $stmt10 = $dbconn->prepare("SELECT * from sexo");
        $stmt10->execute();
        $rows10 = $stmt10->fetchAll(PDO::FETCH_ASSOC);

        $stmt11 = $dbconn->prepare("SELECT * from estado_civil");
        $stmt11->execute();
        $rows11 = $stmt11->fetchAll(PDO::FETCH_ASSOC);


               $response='<div class="card shadow h-100" id="contenedor-'.$id.'">
                            <div class="card-header ">
                                <div class="d-flex justify-content-between">
                                    <h4 class="m-0 font-weight-bold text-primary pt-2">'.$rows2[0]["nombres"].' '.$rows2[0]["apellidoPaterno"].' '.$rows2[0]["apellidoMaterno"].'</h4>
                            
                                    <div class="custom-control custom-switch pt-2">
                                        <input type="checkbox" class="custom-control-input" id="inscribir">
                                        <label class="custom-control-label" for="inscribir">Inscribir</label>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="card-body overflow-auto">
                              
                            <div class="container-fluid ">

                                <!-- contenido del prospecto -->
                                    <div class="row mb-2">

                                            <div class="col-md-4 justify-content-evenly p-3">
                                                <img src="img/categorias/usuarios/avatar_hombre.svg" alt="Imagen" class="image-container rounded-circle" >
                                            </div>

                                            <div class="col-md-8">
                                                
                                                <div class="d-flex justify-content-between">
                                                <h4 class="m-0 font-weight-bold text-primary pt-2">'.$rows2[0]["nombres"].'</h4>
                                                <button class="btn btn-primary">Actualizar</button>
                                                </div>

                                            </div>
                                    </div>


                                    <div class="row">
                                            <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" required autocomplete="off" value="'.$rows2[0]["nombres"].'"> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="apellidoPaterno">Apellido Paterno</label>
                                        <input type="text" class="form-control" id="apellidoPaterno" required autocomplete="off" value="'.$rows2[0]["apellidoPaterno"].'">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="apellidoMaterno">Apellido Materno</label>
                                        <input type="text" class="form-control" id="apellidoMaterno" required autocomplete="off" value="'.$rows2[0]["apellidoMaterno"].'">
                                    </div>
                                    </div>
                                            </div>


                                
                        <div class="row">
                            <!-- Segunda Fila: Nivel (select), Modalidad (select), Ciclo -->
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="campus">Campus</label>
                                <select class="form-control" id="campus">';

                                foreach($rows5 as $row){
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["campus"].'</option>';
                                }  
                                $response=$response.' </select>
                                    </div>
                                </div>


                                 <div class="col-md-4">
                                <div class="form-group">
                                <label for="nivel">Nivel</label>
                                <select class="form-control" id="nivel" required onchange="listas1()">
                                <option value="0">SELECCIONAR NIVEL</option>';

                                foreach($rows as $row){
                                    if($row["id"] == $rows1[0]["nivel"]){
                                    $response=$response.' <option value="'.$row["id"].'" selected>'.$row["nombre"].'</option>';

                                    }else{
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';

                                    }
                                }  
                                $response=$response.' </select>
                                    </div>
                                </div>

                        
                            <div class="col-md-4">
                            <div class="form-group">
                                <label for="carrera">Carrera</label>
                                <select class="form-control" id="carrera" required  onchange="listas2()">
                                <option value="0">SELECCIONAR CARRERA</option>';
                                foreach($rows8 as $row){
                                    if($row["id"] == $rows1[0]["carrera"]){
                                    $response=$response.' <option value="'.$row["id"].'" selected>'.$row["nombre"].'</option>';

                                    }else{
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';

                                    }
                                }  
                                $response=$response.' </select>
                                </select>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modalidad">Modalidad</label>
                                <select class="form-control" id="modalidad" required >
                                <option value="0">SELECCIONAR MODALIDAD</option>';
                                foreach($rows9 as $row){
                                    if($row["id"] == $rows1[0]["modalidad"]){
                                    $response=$response.' <option value="'.$row["id"].'" selected>'.$row["nombre"].'</option>';

                                    }else{
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';

                                    }
                                } 
                               $response=$response.'</select>
                                        </div>
                                    </div>

                                     <div class="col-md-6">
                                    <div class="form-group">
                                <label for="ciclo">Ciclo</label>
                                <select class="form-control" id="ciclo" required>
                                <option value="0">SELECCIONAR CICLO</option>';

                                foreach($rows4 as $row){
                                    if($row["id"] == $rows2[0]["ciclo"]){
                                     $response=$response.' <option value="'.$row["id"].'" selected >'.$row["nomenclatura"].'</option>';

                                    }else{
                                        $response=$response.' <option value="'.$row["id"].'">'.$row["nomenclatura"].'</option>';
                                    }
                                }      
                               $response=$response.' </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <!-- Tercera Fila: Teléfono, Correo Electrónico -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="canal">Canal de comunicación</label>
                                <select class="form-control" id="canal" required>
                                <option value="0">SELECCIONAR...</option>';

                                foreach($rows6 as $row){
                                    if($row["id"] == $rows2[0]["canal"]){
                                    $response=$response.' <option value="'.$row["id"].'" selected >'.$row["nombre"].'</option>';

                                    }else{
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';

                                    }
                                }      
                               $response=$response.' </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                         <label for="difusion">Medio de difusión</label>
                                <select class="form-control" id="difusion" required>
                                <option value="0">SELECCIONAR...</option>';

                                foreach($rows7 as $row){
                                    if($row["id"] == $rows2[0]["difusion"]){
                                    $response=$response.' <option value="'.$row["id"].'" selected >'.$row["nombre"].'</option>';

                                    }else{
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';

                                    }
                                }      
                               $response=$response.' </select>
                                        </div>
                                    </div>
                                </div>

                                 <div class="row">
                                    <!-- Tercera Fila: Teléfono, Correo Electrónico -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono</label>
                                            <input type="text" class="form-control" id="telefono" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10" required autocomplete="off" value="'.$rows2[0]["telefono"].'">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" required autocomplete="off" value="'.$rows2[0]["correo"].'">
                                        </div>
                                    </div>
                                </div>
                        </div>


                    <div class="row m-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="1">Código Postal</label>
                            <div class="input-group ">
                                <input type="text" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="5" id="1" autocomplete="off" value="'.$rows2[0]["cp"].'">
                                <span class="input-group-text" id="basic-addon2" style="background-color: #2e59d9;" onclick="codigoPostal()"><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="2">Calle</label>
                            <input type="text" class="form-control" id="2" autocomplete="off" value="'.$rows2[0]["calle"].'">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="6">Numero ext.</label>
                            <input type="text" class="form-control" id="6" autocomplete="off" data-toggle="tooltip"  data-placement="top" title="Si no cuenta con numero, ingresa 0" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="5" value="'.$rows2[0]["numext"].'">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="7">Numero Int.</label>
                            <input type="text" class="form-control" id="7" autocomplete="off" data-toggle="tooltip" data-placement="top" title="Si no cuenta con numero, ingresa 0" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="5" value="'.$rows2[0]["numint"].'">
                        </div>
                    </div>
                </div>

                <div class="row m-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="3">Localidad</label>
                            <select class="form-control" id="3">
                            <option value="'.$rows2[0]["localidad"].'">'.$rows2[0]["localidad"].'</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="4">Municipio</label>
                            <input type="text" class="form-control" id="4" disabled value="'.$rows2[0]["municipio"].'">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="5">Estado</label>
                            <input type="text" class="form-control" id="5" disabled value="'.$rows2[0]["estado"].'">
                        </div>
                    </div>
                </div>

                <div class="row m-3">
                
                 <div class="col-md-4">
                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <select class="form-control" id="sexo">
                            <option value="0" selected >SELECIONA EL SEXO</option>';

                                foreach($rows10 as $row){
                                    if($row["id"] == $rows2[0]["sexo"]){
                                    $response=$response.' <option value="'.$row["id"].'" selected >'.$row["nombre"].'</option>';

                                    }else{
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';

                                    }
                                }      
                               $response=$response.'
                            </select>
                        </div>
                </div>

                <div class="col-md-4">
                        <div class="form-group">
                            <label for="estado_civil">Estado Civil</label>
                            <select class="form-control" id="estado_civil">
                            <option value="0" selected >SELECIONA EL ESTADO CIVIL</option>';

                                foreach($rows11 as $row){
                                    if($row["id"] == $rows2[0]["estado_civil"]){
                                    $response=$response.' <option value="'.$row["id"].'" selected >'.$row["nombre"].'</option>';

                                    }else{
                                    $response=$response.' <option value="'.$row["id"].'">'.$row["nombre"].'</option>';

                                    }
                                }      
                               $response=$response.'
                            </select>
                        </div>
                </div>

                <div class="col-md-4">
                        <div class="form-group">
                            <label for="sangre">Tipo de sangre</label>
                                <input type="text" class="form-control" id="sangre" autocomplete="off" value="'.$rows2[0]["sangre"].'">
                        </div>
                </div>

                </div>

                <div class="row m-3">
                
                 <div class="col-md-4">
                        <div class="form-group">
                            <label for="curp">CURP</label>
                            <input type="text" class="form-control" id="curp" autocomplete="off" value="'.$rows2[0]["curp"].'">
                        </div>
                </div>

                <div class="col-md-4">
                        <div class="form-group">
                            <label for="rfc">RFC</label>
                              <input type="text" class="form-control" id="rfc" autocomplete="off" value="'.$rows2[0]["rfc"].'">
                        </div>
                </div>

                <div class="col-md-4">
                        <div class="form-group">
                            <label for="fnacimiento">Fecha de nacimiento</label>
                              <input type="date" class="form-control" id="fnacimiento" autocomplete="off" value="'.$rows2[0]["fecha_nacimiento"].'">
                        </div>
                </div>

                </div>


                                <!-- contenido del prospecto -->
                                </div>
                            </div>
                        </div>';
                    
                echo $response;
        break;




         case 7:
            $id=$_POST["id"];

            $nombre=$_POST["nombre"];
            $apellidoPaterno=$_POST["apellidoPaterno"];
            $apellidoMaterno=$_POST["apellidoMaterno"];
            $rol=$_POST["rol"];
            $campus=$_POST["campus"];
            $usuario=$_POST["usuario"];
            $password=$_POST["password"];
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
                $query = "UPDATE alumno set nombre= '".$nombre."',apellidoP= '".$apellidoPaterno."',apellidoM= '".$apellidoMaterno."',cuenta= '".$usuario."',correo= '".$correo."',password= '".$password."',telefono= '".$telefono."',campus= '".$campus."',rol= '".$rol."',cp= '".$cp."',calle= '".$calle."',numeroext= '".$numext."',numeroint= '".$numint."',localidad= '".$localidad."',municipio= '".$municipio."',estado= '".$estado."',qr= '".$codigo."' WHERE id='".$id."'";
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
    }