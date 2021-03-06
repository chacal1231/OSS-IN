<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
require 'inc/PHPMailer/PHPMailerAutoload.php';
if(isset($_POST['post'])){
	$can   =   $_POST['can'];
    $um    =   $_POST['um'];
    $ref   =   $_POST['ref'];
    $des   =   $_POST['des'];
    $tp    =   $_POST['tp'];
    $prio  =   $_POST['prio'];
    $obs   =   $_POST['obs'];
    $proy  =   $_POST['proy'];
    $equipo =  $_POST['equipo'];
    $mes = date("F");
    if ($mes=="January") $mes="Enero";
    if ($mes=="February") $mes="Febrero";
    if ($mes=="March") $mes="Marzo";
    if ($mes=="April") $mes="Abril";
    if ($mes=="May") $mes="Mayo";
    if ($mes=="June") $mes="Junio";
    if ($mes=="July") $mes="Julio";
    if ($mes=="August") $mes="Agosto";
    if ($mes=="September") $mes="Septiembre";
    if ($mes=="October") $mes="Octubre";
    if ($mes=="November") $mes="Noviembre";
    if ($mes=="December") $mes="Diciembre";

    //Mysql
    $result     =   mysqli_query($link,"SELECT * FROM req ORDER BY id DESC");
    $row        =   mysqli_fetch_array($result);            
    $id         =   ($row["id"]+1);
    //Productos
    $result_p   =   mysqli_query($link,"SELECT * FROM req_productos ORDER BY id DESC");
    $row_p      =   mysqli_fetch_array($result_p);            
    $id_pp      =   ($row_p["id"]+1);
    $con        =   "OSS-REQ-" .$id;
    //usuario información
    $UserQuery  =   mysqli_query($link,"SELECT * FROM user WHERE id_user='$_SESSION[id]'");
    $RowUser    =   mysqli_fetch_array($UserQuery);
    $cargo      =   $RowUser['cargo'];
    $nom        =   $RowUser['name_user'];
    $fecha_e    =   date('Y-m-d');
    //Prioridad
    if($prio=="Alta"){
        $fecha_r    =   date('Y-m-d', strtotime($stop_date . ' +1 day'));
    }else if($prio=="Media"){
        $fecha_r    =   date('Y-m-d', strtotime($stop_date . ' +2 day'));
    }else if($prio=="Baja"){
        $fecha_r    =   date('Y-m-d', strtotime($stop_date . ' +3 day'));
    }
    $fecha_res  =   "1970-01-01";
    //Ciclo para obtener datos
    for ($i=0; $i < count($can) ; $i++) {
        mysqli_query($link,"INSERT INTO req_productos(id,con,cant,um,ref,des,tp,precio,precio_u,ti,marca,prov) VALUES('$id_pp','$con','$can[$i]','$um[$i]','$ref[$i]','$des[$i]','$tp[$i]','0','0','','','')");
        $id_pp  =   $id_pp + 1;

    }
    mysqli_query($link,"INSERT INTO req(id,con,nom,cargo,proy,obs,fecha_e,fecha_r,fecha_res,fecha_entre,prio,estado,preciototal,equipo,mes) VALUES ('$id','$con','$nom','$cargo','$proy','$obs','$fecha_e','$fecha_r','$fecha_res','0000-00-00','$prio','En revisión','0','$equipo','$mes')");
    //Correos
    $ConsultaCorreos=mysqli_query($link,"SELECT * FROM correos");
    $RowCorreos=mysqli_fetch_array($ConsultaCorreos);
    //PHPMailer clase
    $mail = new PHPMailer;
    //$mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
    //$mail->Debugoutput = 'html';
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = $smtp;
    $mail->SMTPAuth = $SMTPAuth;
    $mail->Username = $usuarioSmtp;
    $mail->Password = $contraseñaSmtp;
    $mail->SMTPSecure = $SMTPSecure;
    $mail->SMTPAuth   = true;
    $mail->Port = $port;
 
    $mail->setFrom('sistemas@oss.com.co', 'OSS Sistema requisiciones');
    $mail->addAddress("jedmacmahonve@unal.edu.co");
    $mail->Subject = "Nueva requisición $con de $nom";
    $mail->Body = "OSS le informa que tiene una nueva requisición de $nom con prioridad $prio, por favor acceder a inventario.oss.com.co para revisar la requisición $con";    
    $mail->send();
    //Verificación Mysql
    $my_error = mysqli_error($link);
    if(empty($my_error)){
            echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se ha añadido correctamente el producto.</div>';
    }else{
        echo $my_error;
    }
}if(isset($_POST['ver'])){
    echo "<body onLoad=$('#myModal2').modal('show')>";
    $QueryModal     =   mysqli_query($link,"SELECT * FROM req WHERE con='$_POST[ver]'");
    $RowModal       =   mysqli_fetch_array($QueryModal);
    $QueryProductos =   mysqli_query($link,"SELECT * FROM req_productos WHERE con='$_POST[ver]' ORDER BY id ASC");
    $RowProductos   =   mysqli_fetch_array($QueryProductos);
    }
if(isset($_POST['pre-apro'])){
    $id    =   $_POST['id'];
    $can   =   $_POST['can_a'];
    $um    =   $_POST['um_a'];
    $ref   =   $_POST['ref_a'];
    $des   =   $_POST['des_a'];
    $tp    =   $_POST['tp_a'];
    $con   =   $_POST['con'];
    //Ciclo para obtener datos y hacer consulta mysqli
    for ($i=0; $i < count($can) ; $i++) {
        mysqli_query($link,"UPDATE req_productos SET cant=$can[$i] WHERE con='$con' AND id=$id[$i]");
    }
    //Consulta para cambiar el estado
    mysqli_query($link,"UPDATE req SET estado='Pre-aprobada' WHERE con='$con'");

    /*-------------------->Generación del PDF----------------------->*/
    $Query_Con = mysqli_query($link,"SELECT * FROM req WHERE con='$con'");
    $Row_Con   = mysqli_fetch_array($Query_Con);
    // Begin configuration
    $textColour = array( 0, 0, 0 );
    $headerColour = array( 0, 0, 0 );
    $tableHeaderTopTextColour = array( 255, 255, 255 );
    $tableHeaderTopFillColour = array( 100, 152, 179 );
    $tableHeaderTopProductTextColour = array( 0, 0, 0 );
    $tableHeaderTopProductFillColour = array( 80, 185, 46 );
    $tableHeaderLeftTextColour = array( 99, 42, 57 );
    $tableHeaderLeftFillColour = array( 184, 207, 229 );
    $tableBorderColour = array( 50, 50, 50 );
    $tableRowFillColour = array( 213, 170, 170 );
    $reportName = "Reporte $Pozo";
    $reportNameYPos = 160;
    $logoFile = "backend/panel/images/logo.png";
    $logoXPos = 50;
    $logoYPos = 108;
    $logoWidth = 110;$pdf = new FPDF( 'P', 'mm', 'A4' );
    $pdf->SetAutoPageBreak(true,10);
    $pdf->SetTitle("Requisición $con",true);
    $pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );$pdf->AddPage();
    $pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
    $pdf->SetFont( 'Arial', 'B', 13 );
    $pdf->Image('backend/panel/images/logo.png' , 10 ,8, 40 , 20,'PNG');
    $pdf->Cell( 0, 10, "FORMATO", 0, 0, 'C' );
    $pdf->Cell( -190, 20, "HSEQ", 0, 0, 'C' );
    $pdf->Cell( 0, 30, "REQUISICION DE MATERIALES Y EQUIPOS", 0, 0, 'C' );
    $pdf->SetFont( 'Arial', 'B', 10 );
    $pdf -> SetX(155);    // set the cursor at Y position 5
    $pdf->Cell( 0, 10, utf8_decode("Versión 005"), 0, 0, 'C' );
    $pdf -> SetX(166);    // set the cursor at Y position 5
    $pdf->Cell( 0, 20, utf8_decode("Fecha: 2018-02-19"), 0, 0, 'C' );
    $pdf -> SetX(158);    // set the cursor at Y position 5
    $pdf->Cell( 0, 30, utf8_decode("Página 1 de 1"), 0, 0, 'C' );
    $pdf->SetFont( 'Arial', 'B', 15 );
    $pdf->Ln( 10 );
    $pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
    $pdf->SetDrawColor( $tableBorderColour[0], $tableBorderColour[1], $tableBorderColour[2] );
    $pdf->Ln( 35 );
    $pdf->SetFont( 'Arial', 'B', 8 );
    $pdf->SetTextColor( $tableHeaderTopProductTextColour[0], $tableHeaderTopProductTextColour[1], $tableHeaderTopProductTextColour[2] );
    $pdf->SetFillColor( $tableHeaderTopProductFillColour[0], $tableHeaderTopProductFillColour[1], $tableHeaderTopProductFillColour[2] );
    $pdf->Cell( 45, 8, utf8_decode("Fecha de envío:"), 1, 0, 'C', false );
    $pdf->Cell( 30, 8, utf8_decode($Row_Con['fecha_e']), 1, 0, 'C', true );
    $pdf -> SetX(140);
    $pdf->Cell( 30, 8, utf8_decode("Consecutivo:"), 1, 0, 'C', false );
    $pdf->Cell( 30, 8, utf8_decode($con), 1, 0, 'C', true );

    $pdf->Ln(8);
    $pdf->Cell( 45, 8, "Fecha que se requiere solicitud:", 1, 0, 'C', false );
    $pdf->Cell( 30, 8, utf8_decode($Row_Con['fecha_r']), 1, 0, 'C', true );
    $pdf->Ln( 20 );
    $pdf->SetFont( 'Arial', 'B', 10 );
    $pdf->Write(0, 'PROYECTO /CENTRO COSTO: ');
    $pdf->SetFont( 'Arial', 'U', 10 );
    $pdf -> SetX(70);
    $pdf->Write(0, utf8_decode($Row_Con['proy']));
    $pdf->Ln( 8 );
    $pdf->SetFont( 'Arial', 'B', 10 );
    $pdf->Write(0, 'EQUIPO: ');
    $pdf->SetFont( 'Arial', 'U', 10 );
    $pdf -> SetX(70);
    $pdf->Write(0, utf8_decode($Row_Con['equipo']));
    $pdf->Ln( 8 );
    $pdf->SetFont( 'Arial', 'B', 10 );
    $pdf->Write(0, 'NOMBRE DEL SOLICITANTE: ');
    $pdf->SetFont( 'Arial', 'U', 10 );
    $pdf -> SetX(70);
    $pdf->Write(0,utf8_decode($Row_Con['nom']));
    $pdf->Ln( 8 );
    $pdf->SetFont( 'Arial', 'B', 10 );
    $pdf->Write(0, 'CARGO DEL SOLICITANTE: ');
    $pdf->SetFont( 'Arial', 'U', 10 );
    $pdf -> SetX(70);
    $pdf->Write(0, utf8_decode($Row_Con['cargo']));
    $pdf->Ln( 10 );
    $pdf->SetFont( 'Arial', 'B', 8 );
    $pdf->Cell( '10', 8, utf8_decode("ITEM"), 1, 0, 'C', true );
    $pdf->Cell( '16', 8, utf8_decode("CANTIDAD"), 1, 0, 'C', true );
    $pdf->Cell( '30', 8, utf8_decode("UNIDAD DE MEDIDA"), 1, 0, 'C', true );
    $pdf->Cell( '30', 8, utf8_decode("REFERENCIA/TALLA"), 1, 0, 'C', true );
    $pdf->Cell( '80', 8, utf8_decode("DESCRIPCIÓN"), 1, 0, 'C', true );
    $pdf->Cell( '26', 8, utf8_decode("TIPO DE COMPRA"), 1, 0, 'C', true );
    $pdf->Ln(8);
    $i=1;
    $pdf->SetFont( 'Arial', '', 8 );
    $Query = mysqli_query($link,"SELECT * FROM req_productos WHERE con='OSS-REQ-1'");
    while($Row = mysqli_fetch_array($Query)){
        $pdf->Cell( '10', 8, $i , 1, 0, 'C', false );
        $pdf->Cell( '16', 8, $Row['cant'], 1, 0, 'C', false );
        $pdf->Cell( '30', 8, $Row['um'], 1, 0, 'C', false );
        $pdf->Cell( '30', 8, $Row['ref'], 1, 0, 'C', false );
        $pdf->Cell( '80', 8, utf8_decode($Row['des']), 1, 0, 'C', false );
        $pdf->Cell( '26', 8, utf8_decode($Row['tp']), 1, 0, 'C', false );
        $pdf->Ln(8);
        $i++;
    }
    $pdf->Ln(10);
    $pdf->SetFont( 'Arial', 'B', 10 );
    $pdf->Write(0, utf8_decode('OBSERVACIONES: '));
    $pdf->SetFont( 'Arial', '', 8 );
    $pdf->Ln(2);
    $pdf->MultiCell( 190, 5, utf8_decode($Row_Con['obs']), 0);
    $pdf->Ln(30);
    $pdf->SetFont( 'Arial', 'B', 10 );
    $pdf->Write(0, utf8_decode('ELABORADO POR: '));
    $pdf->SetFont( 'Arial', 'U', 10 );
    $pdf->Write(0, utf8_decode($Row_Con['nom']));
    $pdf->SetFont( 'Arial', 'B', 10 );
    $pdf -> SetX(110);
    $pdf->Write(0, utf8_decode('AUTORIZADO POR: '));
    $pdf -> SetX(145);
    $pdf->Write(0, utf8_decode('________________________'));
    $filename="pdf/$con.pdf";
    $pdf->Output($filename,'F');

    //mail
    $mail = new PHPMailer;
    //$mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
    //$mail->Debugoutput = 'html';
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = $smtp;
    $mail->SMTPAuth = $SMTPAuth;
    $mail->Username = $usuarioSmtp;
    $mail->Password = $contraseñaSmtp;
    $mail->SMTPSecure = $SMTPSecure;
    $mail->SMTPAuth   = true;
    $mail->Port = $port;
    $mail->setFrom('sistemas@oss.com.co', 'OSS Sistema requisiciones');
    $mail->addAddress("jedmacmahonve@unal.edu.co");
    $mail->Subject = "Nueva requisición $con de $Row_Con[nom]";
    $mail->Body = "OSS le informa que tiene una nueva requisición de $Row_Con[nom] con prioridad $Row_Con[prio]. Es importante que atienda a este correo y cotice los item's lo más rapido posible.";
    $mail->AddAttachment($filename);    
    $mail->send();
    //Mensaje Pre-aprobar
     echo "<div class='alert alert-success' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>¡Todo correcto!</strong> Se ha pre-aprobado la requisición.</div>";
}
if(isset($_POST['ver-pre'])){
    echo "<body onLoad=$('#myModal3').modal('show')>";
    $Cons = $_POST['ver-pre'];
    $QueryModal3     =   mysqli_query($link,"SELECT * FROM req WHERE con='$Cons'");
    $RowModal3       =   mysqli_fetch_array($QueryModal3);
}
if(isset($_POST['compra'])){
    $con = $_POST['compra'];
    $Kv_items = array(); 
    $Kv = 0;
    $uploads_dir = "uploads/";
    // File Type Restrictions
        $allowed = array( 'jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf', 'docx', 'xls', 'doc', 'xlsx');
        foreach($_FILES['attachment']['name'] as $name) {
            $type = pathinfo($name, PATHINFO_EXTENSION); 
            if(!in_array($type, $allowed)) 
                die("<div class='alert alert-danger' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>¡ERROR!</strong> El formato (<b>$type</b>) de archivo que está intentando subir no es valido.</div>");    
        }
    foreach($_FILES['attachment']['name'] as $filename) {
        $result     =   mysqli_query($link,"SELECT * FROM archivos ORDER BY id DESC");
        $row        =   mysqli_fetch_array($result);            
        $id         =   ($row["id"]+1);
        move_uploaded_file($_FILES["attachment"]["tmp_name"][$Kv], $uploads_dir. basename($filename)); 
        mysqli_query($link, "INSERT INTO archivos(id,nombre,con,fecha) values ($id ,'".$_FILES["attachment"]["name"][$Kv]."', '$con','".date('Y-m-d')."')");
        echo mysqli_error($link);
        $Kv++;
        $Kv_items[] = mysqli_insert_id($link); 
 }
 if(count($Kv_items)){
    $Cantidad = count($Kv_items);
    echo "<div class='alert alert-success' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>¡Todo correcto!</strong> Se han añadido $Cantidad cotizaciones a la requisición $con.</div>";
 }
 //Query para actualizar estado de requisición
 mysqli_query($link,"UPDATE req SET estado='Finalizada' WHERE con='$con'");   
}
if(isset($_POST['ver-precios'])){
    echo "<body onLoad=$('#myModal4').modal('show')>";
    $Cons = $_POST['ver-precios'];
    $QueryModal4     =   mysqli_query($link,"SELECT * FROM req WHERE con='$Cons'");
    $RowModal4       =   mysqli_fetch_array($QueryModal4);
    $QueryProductos4 =   mysqli_query($link,"SELECT * FROM req_productos WHERE con='$Cons' ORDER BY id ASC");
    $RowProductos4   =   mysqli_fetch_array($QueryProductos4);
}
if(isset($_POST['precios'])){
    $Cons   =   $_POST['precios'];
    $id     =   $_POST['id'];
    $precio =   $_POST['precio_a'];
    $can    =   $_POST['can_a'];
    $ti     =   $_POST['ti'];
    $marca  =   $_POST['marca'];
    $prov   =   $_POST['prov'];

    //Ciclo para obtener datos y hacer consulta mysqli
    for ($i=0; $i < count($can) ; $i++) {
        mysqli_query($link,"UPDATE req_productos SET precio=($can[$i]*$precio[$i]), precio_u='$precio[$i]',ti='$ti[$i]',marca='$marca[$i]',prov='$prov[$i]' WHERE con='$Cons' AND id=$id[$i]");
        $preciototal=$preciototal+($can[$i]*$precio[$i]);
    }
    //Actualizar total req
    mysqli_query($link,"UPDATE req SET preciototal=$preciototal WHERE con='$Cons'");
    //Actualizar estado
    mysqli_query($link,"UPDATE req SET estado='Finalizada-1' WHERE con='$Cons'");
    //Mensaje
    echo "<div class='alert alert-success' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>¡Todo correcto!</strong> Se han actualizado correctamente los precios de la requisición.</div>";
}if(isset($_POST['ver-final'])){
    echo "<body onLoad=$('#myModal5').modal('show')>";
    $Cons = $_POST['ver-final'];
    $QueryModal5     =   mysqli_query($link,"SELECT * FROM req WHERE con='$Cons'");
    $RowModal5       =   mysqli_fetch_array($QueryModal5);
    $QueryProductos5 =   mysqli_query($link,"SELECT * FROM req_productos WHERE con='$Cons' ORDER BY id ASC");
    $RowProductos5   =   mysqli_fetch_array($QueryProductos5);
    $QueryFiles      =   mysqli_query($link,"SELECT * FROM archivos WHERE con='$Cons'");
    $RowFiles        =   mysqli_fetch_array($QueryFiles);
    $QueryFiles2     =   mysqli_query($link,"SELECT * FROM archivos_re WHERE con='$Cons'");
    $RowFiles2       =   mysqli_fetch_array($QueryFiles);
}

if(isset($_POST['ver-entrega'])){
    echo "<body onLoad=$('#myModal6').modal('show')>";
    $Cons = $_POST['ver-entrega'];
    $QueryModal6     =   mysqli_query($link,"SELECT * FROM req WHERE con='$Cons'");
    $RowModal6       =   mysqli_fetch_array($QueryModal6);
}
if(isset($_POST['entrega'])){
    $con   = $_POST['entrega'];
    $fecha = $_POST['fecha_e'];
    $Kv_items = array(); 
    $Kv = 0;
    $uploads_dir = "uploads/";
    //Mysql
    mysqli_query($link,"UPDATE req SET fecha_entre='$fecha' WHERE con='$con'");
    mysqli_query($link,"UPDATE req SET estado='Finalizada-2' WHERE con='$con'");
    /* TODO Poner items en el inventario */
    $QueryItems =   mysqli_query($link,"SELECT * FROM req_productos WHERE con='$con' AND tp='Inventario'");
    $RowItems   =   mysqli_fetch_array($QueryItems);
    //Query id inventario
    $fecha_c    =   date('Y-m-d');
    $mes = date("F");
    if ($mes=="January") $mes="Enero";
    if ($mes=="February") $mes="Febrero";
    if ($mes=="March") $mes="Marzo";
    if ($mes=="April") $mes="Abril";
    if ($mes=="May") $mes="Mayo";
    if ($mes=="June") $mes="Junio";
    if ($mes=="July") $mes="Julio";
    if ($mes=="August") $mes="Agosto";
    if ($mes=="September") $mes="Septiembre";
    if ($mes=="October") $mes="Octubre";
    if ($mes=="November") $mes="Noviembre";
    if ($mes=="December") $mes="Diciembre";
    //Variales inventario
    foreach ($QueryItems as $RowItems => $field) {
        /*Variables inventario */
        $ref = time() . rand(10*45, 100*98);
        $result     =   mysqli_query($link,"SELECT * FROM inventario ORDER BY id DESC");
        $row        =     mysqli_fetch_array($result);
        $id         = ($row["id"]+1);
        mysqli_query($link,"INSERT INTO inventario(id,ref,des,marca,ti,prov,fecha_c,mes,precio,total_p,unid,asig,proyecto,locacion,equipo,motivoas) VALUES('$id','$ref','$field[des]','$field[marca]','$field[ti]','$field[prov]','$fecha_c','$mes','$field[precio_u]','$field[precio]','$field[cant]','NA','NA','NA','NA','NA')");
        /* Variables registro inventario */
        $fecha          =   date('Y-m-d');
        $hora           =   date('h:i:s A');
        $result_reg     =   mysqli_query($link,"SELECT * FROM registro ORDER BY id DESC");
        $row_reg        =   mysqli_fetch_array($result_reg);            
        $id_reg         =   ($row_reg["id"]+1);
        $des            =   "COMPRAS agregó el producto $field[des] al inventario por medio de la requisición # $con";
        mysqli_query($link,"INSERT INTO registro(id,fecha,hora,des,ref,total) VALUES('$id_reg','$fecha','$hora','$des','$ref','$field[cant]')");
        echo mysqli_error($link);
    /* TODO */
    }
    // File Type Restrictions
        $allowed = array( 'jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf', 'docx', 'xls', 'doc', 'xlsx');
        foreach($_FILES['attachment']['name'] as $name) {
            $type = pathinfo($name, PATHINFO_EXTENSION); 
            if(!in_array($type, $allowed)) 
                die("<div class='alert alert-danger' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>¡ERROR!</strong> El formato (<b>$type</b>) de archivo que está intentando subir no es valido.</div>");    
        }
    foreach($_FILES['attachment']['name'] as $filename) {
        $result     =   mysqli_query($link,"SELECT * FROM archivos_re ORDER BY id DESC");
        $row        =   mysqli_fetch_array($result);            
        $id         =   ($row["id"]+1);
        move_uploaded_file($_FILES["attachment"]["tmp_name"][$Kv], $uploads_dir. basename($filename)); 
        mysqli_query($link, "INSERT INTO archivos_re(id,nombre,con,fecha) values ($id ,'".$_FILES["attachment"]["name"][$Kv]."', '$con','".date('Y-m-d')."')");
        echo mysqli_error($link);
        $Kv++;
        $Kv_items[] = mysqli_insert_id($link); 
 }
 if(count($Kv_items)){
    $Cantidad = count($Kv_items);
    echo "<div class='alert alert-success' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>¡Todo correcto!</strong> Se han añadido $Cantidad remisiones a la requisición $con.</div>";
 }
}


//Mysql para tabla
$QueryTabla = mysqli_query($link,"SELECT * FROM req ORDER BY id DESC");
$RowTabla   = mysqli_fetch_array($QueryTabla);
?>

<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                Requisiciones
            </header>
            <section class="panel">
                        <div class="panel-body"> 
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Nueva requisición</button>
                            <hr/>
                            <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>Consecutivo</th>
                                            <th>Nombre del solicitante</th>
                                            <th>Fecha de solicitud</th>
                                            <th>Fecha requerimiento</th>
                                            <th>Priodidad</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryTabla as $RowTabla => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['con']; ?></td>
                                            <td><?php echo $field['nom']; ?></td>
                                            <td><?php echo $field['fecha_e']; ?></td>
                                            <td><?php echo $field['fecha_r']; ?></td>
                                            <td> <?php if($field['prio'] == 'Alta'){ ?>
                                                <button type="button" class="btn btn-danger">Alta</button>

                                            <?php } elseif($field['prio'] == 'Media') { ?>
                                            
                                                <button type="button" class="btn btn-warning">Media</button>


                                            <?php } elseif($field['prio'] == 'Baja') { ?>
                                            
                                                <button type="button" class="btn btn-info">Baja</button>

                                            <?php } ?>  
                                            </td>
                                            <td> <?php if($field['estado'] == 'En revisión'){ ?>
                                                <button type="button" class="btn btn-warning">En revisión</button>

                                            <?php } elseif($field['estado'] == 'Pre-aprobada') { ?>
                                            
                                                <button type="button" class="btn btn-info">Pre-aprobada</button>

                                            <?php } else if($field['estado'] == 'En proceso de compra') { ?>
                                            
                                                <button type="button" class="btn btn-primary">En proceso de compra</button>

                                            <?php } else if($field['estado'] == 'Finalizada') { ?>
                                            
                                                <button type="button" class="btn btn-primary">En proceso de compra</button>

                                            <?php } else if($field['estado'] == 'Finalizada-1') { ?>
                                            
                                                <button type="button" class="btn btn-primary">En proceso de entrega</button>

                                            <?php } else if($field['estado'] == 'Finalizada-2') { ?>
                                            
                                                <button type="button" class="btn btn-success">Finalizada</button>

                                            <?php } ?>
                                            </td> 
                                             <td>
                                             <?php if($_SESSION['priv'] == '0' OR $_SESSION['priv'] == '1'){ ?>
                                             <form action="" method="post">
                                             <?php if($field['estado'] == 'En revisión'){ ?>
                                                <button type="submit" class="btn btn-success" name='ver' value='<?php echo $field[con];?>'><i class="fa fa-eye"></i> Ver
                                                </button>
                                                <?php } elseif($field['estado'] == 'Pre-aprobada') { ?>
                                                <button type="submit" class="btn btn-success" name='ver-pre' value='<?php echo $field[con];?>'><i class="fa fa-eye"></i> Adjuntar cotizaciones
                                                </button>
                                                <?php } elseif($field['estado'] == 'Finalizada') { ?>
                                                <button type="submit" class="btn btn-success" name='ver-precios' value='<?php echo $field[con];?>'><i class="fa fa-eye"></i> Actualizar precios
                                                </button>
                                                <?php } elseif($field['estado'] == 'Finalizada-2') { ?>
                                                <button type="submit" class="btn btn-success" name='ver-final' value='<?php echo $field[con];?>'><i class="fa fa-eye"></i> Ver requisición final
                                                </button>
                                                <?php } ?> 
                                                 <?php } ?> 
                                                <?php if($field['estado'] == 'Finalizada-1'){ ?>
                                                        <button type="submit" class="btn btn-success" name='ver-entrega' value='<?php echo $field[con];?>'><i class="fa fa-eye"></i> Adjuntar remisión de entrega
                                                </button>
                                                <?php } ?>
                                                
                                             </form>
                                            </td>
                                                                                        
                                        </tr>
                                        <?php endforeach; ?> <!-- Selesai loop -->                                  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
        </div>
    </section>
</div>



<script type="text/javascript">
function add_row()
{
 $rowno=$("#employee_table tr").length;
 $rowno=$rowno+1;
 $("#employee_table tr:last").after("<tr id='row"+$rowno+"'><td><input type='text' class='form-control' name='can[]' required></td><td><input type='text' class='form-control' name='um[]' required></td><td><input type='text' name='ref[]' class='form-control' required></td><td><input type='text' name='des[]' class='form-control' required ></td><<td><select id='direccion' name='tp[]' class='form-control'><option value=''>----</option><option value='Consumible'>Consumible</option><option value='Inventario'>Inventario</option><option value='Servicio'>Servicio</option></select></td><td><input type='button' value='-' onclick=delete_row('row"+$rowno+"')></td></tr>");
}
function delete_row(rowno)
{
 $('#'+rowno).remove();
}
</script>


<?php
$result2 = mysqli_query($link,"SELECT * FROM proyectos");
$row2 = mysqli_fetch_array($result2) or die(mysqli_error());
?>


<?php
$QueryEquipos  = mysqli_query($link,"SELECT * FROM equipos ORDER BY id ASC");
$RowEquipos  = mysqli_fetch_array($QueryEquipos);
?>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nueva requisición</h4>
      </div>
        <div class="modal-body">
            <form id="modal-form" action="" method="post">
            <label for="recipient-name" class="control-lab  el"><b>Prioridad *:</b></label>
            <select id="direccion" name="prio" class="form-control">
                <option value="">-- Seleccionar --</option>
                <option value="Alta">Alta</option>
                <option value="Media">Media</option>
                <option value="Baja">Baja</option>
            </select>
            <br>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Proyecto/Centro de costo *:</b></label>
                <select id="proy" name="proy" class="form-control">
                                <?php
                                
                                    do 
                                    {
                                        ?>
                                        <option value="<?php echo $row2['nombre']?>">
                                        <?php echo $row2['nombre']; ?>
                                        </option>
                                        <?php
                                    }while ($row2 = $result2->fetch_assoc())   ?>   
                                
                                </select>
            </div>
            <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Equipo *:</b></label>
            <select id="plan" name="equipo" class="form-control">
                <?php
                do{
                    ?>
                      <option value="No Asignado">NA</option>
                      <option value="<?php echo $RowEquipos['serial']?>">
                          <?php echo $RowEquipos['serial']; ?>
                        </option>
                        <?php
                        }while ($RowEquipos = $QueryEquipos->fetch_assoc())   ?>
            </select>
      </div>

            <br>
            <div class="table-responsive">
                                <table  class="table table-bordered table-hover" id="employee_table">
                                    <thead>
                                        <tr class="custom">
                                            <th>Cantidad</th>
                                            <th>Unidad de medida</th>
                                            <th>Referencia/Talla</th>
                                            <th>Descripción</th>
                                            <th>Tipo de compra</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row1">
                                            <td><input type="text" class="form-control" name="can[]" required></td>
                                            <td><input type="text" class="form-control" name="um[]" required></td>
                                            <td><input type="text" class="form-control" name="ref[]" required></td>
                                            <td><input type="text" class="form-control" name="des[]" required></td>
                                            <td><select id="direccion" name="tp[]" class="form-control"><option value="">----</option><option value="Consumible">Consumible</option><option value="Inventario">Inventario</option><option value="Servicio">Servicio</option></select></td>
                                        </tr>                                
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <input type="button" class="btn btn-success" onclick="add_row();" value="Agregar item">
                 <br>
                 <br>
        <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Observaciones *:</b></label>
                <textarea class="form-control" rows="5" name="obs" required></textarea>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success btn-lg" name="post" value="Sign up">Enviar</button>
        </div>
            </form>
        </div>
     </div>
    </div>
</div>



<!-- Modal2 -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Requisición # <b><?php echo $RowModal['con']; ?></b></h4>
      </div>
        <div class="modal-body">
        <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Nombre del solicitante :</b></label>
                <b><input type="text" class="form-control" value="<?php echo $RowModal['nom']; ?>" readonly></b>
        </div>
        <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Prioridad de la requisición :</b></label>
                <b><input type="text" class="form-control" value="<?php echo $RowModal['prio']; ?>" readonly></b>
        </div>
         <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Estado de la requisición :</b></label>
                <b><input type="text" class="form-control" value="<?php echo $RowModal['estado']; ?>" readonly></b>
        </div>
        <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Fecha en la que se requiere la solicitud :</b></label>
                <b><input type="text" class="form-control" value="<?php echo $RowModal['fecha_r']; ?>" readonly></b>
        </div>
            <form id="modal-form" action="" method="post">
            <br>
            <div class="table-responsive">
                                <table  class="table table-bordered table-hover" id="employee_table">
                                    <thead>
                                        <tr class="custom">
                                            <th>Item</th>
                                            <th>Cantidad</th>
                                            <th>Unidad de medida</th>
                                            <th>Referencia/Talla</th>
                                            <th>Descripción</th>
                                            <th>Tipo de compra</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryProductos as $RowProductos => $field ) : ?>
                                            <tr class="text-besar">
                                            <td><input type="text" size="1" class="form-control" name="id[]" value="<?php echo $field[id];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="can_a[]" value="<?php echo $field[cant];?>" required></td>
                                            <td><input type="text" class="form-control" name="um_a[]" value="<?php echo $field[um];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="ref_a[]" value="<?php echo $field[ref];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="des_a[]" value="<?php echo $field[des];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="tp_a[]" value="<?php echo $field[tp];?>" readonly></td>
                                            </tr>
                                        <?php endforeach; ?>                                
                                    </tbody>
                                </table>
                            </div>
                            <br>
                 <br>
                 <br>
        <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Observaciones *:</b></label>
                <textarea class="form-control" rows="5" name="obs"><?php echo $RowModal[obs];?></textarea>
                <input type="hidden" name="con" value="<?php echo $RowModal['con']; ?>" />
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success btn-lg" name="pre-apro" value="Pre-aprobar">Pre-Aprobar</button>
        </div>
            </form>
        </div>
     </div>
    </div>
</div>

<style type="text/css">
    
input[type="file"] {
  position: absolute;
  font-size: 50px;
  opacity: 0;
  right: 0;
  top: 0;
}
</style>

<script type="text/javascript">
    updateList = function() {
  var input = document.getElementById('fileInput');
  var output = document.getElementById('fileList');

  output.innerHTML = '<ul>';
  for (var i = 0; i < input.files.length; ++i) {
    output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
  }
  output.innerHTML += '</ul>';
}
</script>

<!-- Modal3 -->
<div id="myModal3" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Proceso de compra para la requisición # <b><?php echo $RowModal3['con']; ?></b></h4>
      </div>
        <div class="modal-body">
        <form id="modal-form" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Seleccione los archivos que desea adjuntar al proceso de compra.</b></label>
                <br>
                <div class="fileUpload btn btn-success">
                    <span class="fa fa-upload">Seleccionar archivos</span>
                        <input type="file" name="attachment[]" id="fileInput" multiple onchange="javascript:updateList()" />
                    </span>
                </div>
                <div id="fileList"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success btn-lg" name="compra" value="<?php echo $RowModal3['con']; ?>">Adjuntar cotizaciones</button>
        </div>
            </form>
        </div>
     </div>
    </div>
</div>

<script>
$(document).ready(function(){
 
 $('#prov').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"fetch.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
  }
 });
 
});
</script>

<!-- Modal4 -->
<div id="myModal4" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Actualización de precios para la requisición # <b><?php echo $RowModal4['con']; ?></b></h4>
      </div>
        <div class="modal-body">
        <form id="modal-form" action="" method="post">
        <div class="table-responsive">
                                <table  class="table table-bordered table-hover" id="employee_table">
                                    <thead>
                                        <tr class="custom">
                                            <th>Item</th>
                                            <th>Cantidad</th>
                                            <th>Unidad medida</th>
                                            <th>Descripción</th>
                                            <th>Tipo compra</th>
                                            <th>Tipo item</th>
                                            <th>Marca</th>
                                            <th>Proveedor</th>
                                            <th>Precio (Pesos)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryProductos4 as $RowProductos4 => $field ) : ?>
                                            <tr class="text-besar">
                                            <td><input type="text" size="1" class="form-control" name="id[]" value="<?php echo $field[id];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="can_a[]" value="<?php echo $field[cant];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="um_a[]" value="<?php echo $field[um];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="des_a[]" value="<?php echo $field[des];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="tp_a[]" value="<?php echo $field[tp];?>" readonly></td>
                                            <td>
                                                <select id="ti" name="ti[]" class="form-control" required>
                                                    <option value="">-- Seleccionar --</option>
                                                    <option value="Equipo">Equipo</option>
                                                    <option value="Consumible">Consumible</option>
                                                    <option value="Aceite">Aceite</option>
                                                    <option value="Lubricante">Lubricante</option>
                                                    <option value="Herramienta">Herramienta</option>
                                                    <option value="Repuesto">Repuesto</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control" name="marca[]" required></td>
                                            <td><input type="text" class="form-control" name="prov[]" id="prov" required></td>
                                            <td><input type="text" class="form-control" name="precio_a[]" required></td>
                                            </tr>
                                        <?php endforeach; ?>                                
                                    </tbody>
                                </table>
                            </div>
                            <br>
        <br>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success btn-lg" name="precios" value="<?php echo $RowModal4['con']; ?>">Actualizar precios</button>
        </div>
            </form>
        </div>
     </div>
    </div>
</div>



<!-- Modal5 -->
<div id="myModal5" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Resumen de requisición # <b><?php echo $RowModal5['con']; ?></b></h4>
      </div>
        <div class="modal-body">
        <form id="modal-form" action="" method="post">
        <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Fecha de solicitud :</b></label>
                <b><input type="text" class="form-control" value="<?php echo $RowModal5['fecha_e']; ?>" readonly></b>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Fecha de respuesta :</b></label>
                <b><input type="text" class="form-control" value="<?php echo $RowModal5['fecha_r']; ?>" readonly></b>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Fecha de entrega :</b></label>
                <b><input type="text" class="form-control" value="<?php echo $RowModal5['fecha_entre']; ?>" readonly></b>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Costo de la requisición :</b></label>
                <b><input type="text" class="form-control" value="<?php echo number_format((float)$RowModal5['preciototal']); ?> Pesos" readonly></b>
            </div>
            <br>
            <div align="center"><h2><b>Productos solicitados en la requisicion</h2></b></div>
            <br>
        <div class="table-responsive">
                                <table  class="table table-bordered table-hover" id="employee_table">
                                    <thead>
                                        <tr class="custom">
                                            <th>Item</th>
                                            <th>Cantidad</th>
                                            <th>Unidad de medida</th>
                                            <th>Referencia/Talla</th>
                                            <th>Descripción</th>
                                            <th>Tipo de compra</th>
                                            <th>Precio (Pesos)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryProductos5 as $RowProductos5 => $field ) : ?>
                                            <tr class="text-besar">
                                            <td><input type="text" size="1" class="form-control" name="id[]" value="<?php echo $field[id];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="can_a[]" value="<?php echo $field[cant];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="um_a[]" value="<?php echo $field[um];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="ref_a[]" value="<?php echo $field[ref];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="des_a[]" value="<?php echo $field[des];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="tp_a[]" value="<?php echo $field[tp];?>" readonly></td>
                                            <td><input type="text" class="form-control" name="precio_a[]" value="<?php echo number_format((float)$field['precio']);;?>" readonly></td>
                                            </tr>
                                        <?php endforeach; ?>                                
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <br>
            <div align="center"><h2><b>Cotizaciones</h2></b></div>
            <br>
        <div class="table-responsive">
                                <table  class="table table-bordered table-hover" id="employee_table">
                                    <thead>
                                        <tr class="custom">
                                            <th>Id</th>
                                            <th>Nombre archivo</th>
                                            <th>Fecha adjuntado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryFiles as $RowFiles => $field ) : ?>
                                            <tr class="text-besar">
                                            <td><?php echo $field['id']; ?></td>
                                            <td><?php echo $field['nombre']; ?></td>
                                            <td><?php echo $field['fecha']; ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-xs" target="_blank" href="uploads/<?php echo $field['nombre']; ?>" title="Ver datos de <?php echo $field['nombre']; ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                            </tr>
                                        <?php endforeach; ?>                                
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <br>
         <div align="center"><h2><b>Remisiones de entrega</h2></b></div>
         <div class="table-responsive">
                                <table  class="table table-bordered table-hover" id="employee_table">
                                    <thead>
                                        <tr class="custom">
                                            <th>Id</th>
                                            <th>Nombre archivo</th>
                                            <th>Fecha adjuntado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryFiles2 as $RowFiles2 => $field ) : ?>
                                            <tr class="text-besar">
                                            <td><?php echo $field['id']; ?></td>
                                            <td><?php echo $field['nombre']; ?></td>
                                            <td><?php echo $field['fecha']; ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-xs" target="_blank" href="uploads/<?php echo $field['nombre']; ?>" title="Ver datos de <?php echo $field['nombre']; ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                            </tr>
                                        <?php endforeach; ?>                                
                                    </tbody>
                                </table>
                            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Cerrar</button>
        </div>
            </form>
        </div>
     </div>
    </div>
</div>

<style type="text/css">
    .fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}
</style>

<script type="text/javascript">
    updateList2 = function() {
  var input = document.getElementById('fileInput2');
  var output = document.getElementById('fileList2');

  output.innerHTML = '<ul>';
  for (var i = 0; i < input.files.length; ++i) {
    output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
  }
  output.innerHTML += '</ul>';
}
</script>

<!-- Modal6 -->
<div id="myModal6" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Remisión de entrega para la requisición # <b><?php echo $RowModal6['con']; ?></b></h4>
      </div>
        <div class="modal-body">
        <form id="modal-form" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Seleccione los archivos que desea adjuntar a la remisión de entrega</b></label>
                <br>
                <div class="fileUpload btn btn-success">
                    <span class="fa fa-upload">Seleccionar archivos</span>
                        <input type="file" name="attachment[]" id="fileInput2" multiple onchange="javascript:updateList2()" />
                </div>
                <div id="fileList2"></div>
        </div>
        <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Fecha de entrega*:</b></label>
            <input type="text" class="form-control" id="datepicker" data-date-format="yyyy-mm-dd" readonly="readonly" name="fecha_e" required>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success btn-lg" name="entrega" value="<?php echo $RowModal6['con']; ?>">Adjuntar remisión</button>
        </div>
            </form>
        </div>
     </div>
    </div>
</div>