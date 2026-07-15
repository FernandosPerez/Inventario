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
                        <h1 class="h3 mb-0 text-gray-800">Rastreo</h1>
                        <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addProspectModal" onclick="agregar()">
                            <i class="fas fa-download fa-sm text-white-50"></i> Agregar prospecto
                        </a>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Realizar seguimiento</h6>
                        </div>
                        <div class="card-body">
                            
                            <div class="container-fluid">
                                <h3>Mapa</h3>
                                <div id="map" style="height: 400px;width: 100%"></div>

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
    <script type="module"  src="js/mapa.js"></script>
    <script>
    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "AIzaSyBK63cN4HO8Y5BZLR6D1TwLrVEkWNUsvU0",
        v: "weekly",
        // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
        // Add other bootstrap parameters as needed, using camel case.
    });
    </script>
    <script src="js/promocion.js"></script>
    <script src="js/html5-qrcode.min.js"></script>
    
</body> 

</html>