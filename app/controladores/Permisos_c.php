<?php
// Cargar clases del PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require ROOT . 'app/assets/libs/PHPMailer/src/Exception.php';
require ROOT . 'app/assets/libs/PHPMailer/src/PHPMailer.php';
require ROOT . 'app/assets/libs/PHPMailer/src/SMTP.php';
// Cargar clase icalendar
require_once(ROOT . "app/assets/libs/icalendar/zapcallib.php");

class Permisos_c extends Controller
{
    private $permisos_m;
    private $config_m;

    public function __construct()
    {
        if (!isset($_SESSION['sesion'])) header("location:" . BASE_URL . "Usuarios_c/login");
        $this->permisos_m = $this->load_model("Permisos_m"); // Modelos permisos y config
        $this->config_m = $this->load_model("Config_m");
    }
    public function index()
    {
        if (!isset($_SESSION['sesion'])) header("location:" . BASE_URL . "Usuarios_c/login");

        // Si el rol es Secretaria
        if ($_SESSION['sesion']['rol'] == "S") {
            $contenido = "registro_v";
            $datos['permisos'] = $this->permisos_m->leerPermisosRegistro();
        } else {
            // Si no
            $contenido = "permisos_v";
            $datos['permisos'] = $this->permisos_m->leerPermisos($_SESSION['sesion']['usuario']);
            // PermisosValidos solo tienen los que cuentan, sin anulados ni denegados
            $datos['permisosValidos'] = $this->permisos_m->leerPermisosValidos($_SESSION['sesion']['usuario']);
            // Variables para calcular en la vista
            $datos['diaslectivos'] = 0;
            $datos['numerop'] = 0;
        }

        // Visualizar la pagina de aterrizaje

        $datos['config'] = $this->config_m->leerConfig();
        $this->load_view("plantilla/cabecera");
        $this->load_view($contenido, $datos);
        $this->load_view("plantilla/pie");
    }
    public function modificar()
    {
        // Esta función vale para modificar permisos. Hay que tener en cuenta el estado y si tiene faltas anotadas
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        // Leer datos del usuario
        $usuarios_m = $this->load_model("Usuarios_m");
        $usuario = $usuarios_m->leer($_REQUEST['usuario']);
        // Si es concedido y no tiene movimiento en faltas, insertarlo
        if ($_REQUEST['estado'] == "C" and empty($_REQUEST['falta_id'])) {
            // leer Config
            $configuracion = $this->config_m->leerConfig();
            // leer usuario para coger el codigoprof
            $datosFalta = [
                "fecha" => $_REQUEST['fecha_pedida'],
                "profesor" => $usuario['codigoprof'],
                "tramos" => "1,2,3,4,5,6,7",
                "causa" => $configuracion['codCausa'],
                "guardiaSN" => 1
            ];
            if (CONGUARDIAS) {
                // Insertar Falta
                $falta_id = $this->permisos_m->insertarFalta($datosFalta);
                // Obtener el id de la falta
                $_REQUEST['falta_id'] = $falta_id;
            } else {
                $_REQUEST['falta_id'] = null;
            }
            // apunte icalendar
            $ical = $this->crearApunteiCalendar($_REQUEST['fecha_pedida'] . "08:30:00", $_REQUEST['fecha_pedida'] . "14:30:00");
            // Nombre fichero
            $fichero = ROOT . 'app/assets/documentos/' . $_REQUEST['id'] . '.ics';
            // Escribe el contenido ical al fichero
            file_put_contents($fichero, $ical);
            // Correo de concesion al profesor
            if (!empty($usuario['email'])) $this->enviarCorreo($usuario['email'], $usuario['apenom'], "Solicitud permiso Concedida", "<h3>Tu solicitud de permiso particular para la fecha " . date("d/m/Y", strtotime($_REQUEST['fecha_pedida'])) . " ha sido concedida</h3>", $fichero);
        }
        // Si el estado es Denegado y tiene falta puesta
        if ($_REQUEST['estado'] == "D" and !empty($_REQUEST['falta_id'])) {
            if (CONGUARDIAS) {
                // Borrar la falta
                $this->permisos_m->borrarFalta($_REQUEST['falta_id']);
            }
            // Quitar Numero de peticion y id de falta
            $_REQUEST['falta_id'] = null;
            $_REQUEST['npeticion'] = 0;
        }
        // Si es denegado sin falta
        if ($_REQUEST['estado'] == "D" and empty($_REQUEST['falta_id'])) {
            //Guardar causa de denegacion
            $causad = $_REQUEST['causad'];
            // Enviar correo de denegacion
            $cuerpoMensaje = "<p>A la vista de la solicitud de día por asuntos particulares para la fecha " . date("d/m/Y", strtotime($_REQUEST['fecha_pedida'])) . " entregada en este centro el " . date("d/m/Y", strtotime($_REQUEST['fecha_peticion'])) . " , se comunica al interesado que se <b>DENIEGA</b> el permiso del día solicitado por $causad</p><br><br><p>En Cáceres a " . date('d') . " de " . $meses[date('n') - 1] . " de " . date('Y') . "</p><br>";
            if (!empty($usuario['email'])) $this->enviarCorreo($usuario['email'], $usuario['apenom'], "Denegación de día por asuntos particulares", $cuerpoMensaje);
            // Quitar numero de peticion
            $_REQUEST['npeticion'] = 0;
        }
        unset($_REQUEST['causad']); // Quitar la causa del array
        // Modificar el registro
        $this->permisos_m->modificar($_REQUEST);
        header("location:" . $_SERVER['HTTP_REFERER']);
    }

    public function mantpermisos()
    {
        // Solo se puede entrar si se ha hecho login y si tenemos rol de Director
        if ($_SESSION['sesion']['rol'] != "D") header("location:" . BASE_URL . "Permisos_c/index");

        // Cargar modelo Usuarios para selects
        $usuarios_m = $this->load_model("Usuarios_m");
        $datos['usuarios'] = $usuarios_m->leerTodos();
        // Obtener total de permisos
        $datos['permisos'] = $this->permisos_m->leerTodosPermisos();
        // Visualizar la pagina de catalogo
        $contenido = "mantpermisos_v";
        $this->load_view("plantilla/cabecera");
        $this->load_view($contenido, $datos);
        $this->load_view("plantilla/pie");
    }

    public function borrarLinea()
    {
        // Leer el registro en cuestion a borrar
        $regper = $this->permisos_m->leerPermiso($_REQUEST['id']);
        if (CONGUARDIAS) {
            // Borrar la falta si existe
            if (!empty($regper['falta_id'])) {
                $this->permisos_m->borrarFalta($regper['falta_id']);
            }
        }
        echo $this->permisos_m->borrar($_REQUEST['id']);
        // Borrar el pdf con la solicitud
        $fichero = ROOT . 'app/assets/documentos/' . $_REQUEST['id'] . '.pdf';
        unlink($fichero);
    }
    public function anularLinea()
    {
        // Leer el registro en cuestion para anular
        $regper = $this->permisos_m->leerPermiso($_REQUEST['id']);
        if (CONGUARDIAS) {
            // Borrar la falta si existe
            if (!empty($regper['falta_id'])) {
                $this->permisos_m->borrarFalta($regper['falta_id']);
            }
        }
        // Modificar estado a Anulado y numero de peticion a 0
        echo $this->permisos_m->modificar(["id" => $_REQUEST['id'], "estado" => "A", "npeticion" => 0, "dia_lectivo" => 0]);
    }
    public function registrar()
    {
        // Guardar datos de registro
        $this->permisos_m->modificar($_REQUEST);
        // Leer la solicitud para sacar datos del usuario 
        $permiso = $this->permisos_m->leerPermiso($_REQUEST['id']);
        $usuarios_m = $this->load_model("Usuarios_m");
        $usuario = $usuarios_m->leer($permiso['usuario']);
        $adjunto = ROOT . 'app/assets/documentos/' . $permiso['id'] . '.pdf';
        // Enviar correo al profesor en cuestion
        if (!empty($usuario['email'])) $this->enviarCorreo($usuario['email'], $usuario['apenom'], 'Registro de solicitud de permiso', "<h3>Tu solicitud de permiso particular para la fecha " . date("d/m/Y", strtotime($permiso['fecha_pedida'])) . " ha sido registrada con el numero '$permiso[nregistro]'</h3>", $adjunto);
        // leer Config
        $configuracion = $this->config_m->leerConfig();
        // Enviar correo al director para avisar
        if (!empty($configuracion['email_direccion'])) $this->enviarCorreo($configuracion['email_direccion'], "Direccion del centro", 'Aviso de solicitudes de permiso para resolver', '<h3>Hay nuevas solicitudes de permisos particulares para resolver</h3>');
        header("location:" . $_SERVER['HTTP_REFERER']);
    }
    public function leerPermisosFecha()
    {
        // Ajax. Lee todos los permisos de una fecha
        echo json_encode($this->permisos_m->leerPermisosFecha($_REQUEST['fecha']));
    }
    public function leerPermisosUsu()
    {
        // Ajax. Lee todos los permisos de un usuario
        echo json_encode($this->permisos_m->leerPermisos($_REQUEST['usu']));
    }
    public function leerPermisosFechaDet()
    {
        // Ajax.Leer todos los permisos de una fecha detallados
        echo json_encode($this->permisos_m->leerPermisosFechaDet($_REQUEST['fecha']));
    }
    public function leerPermiso()
    {
        // Ajax. Leer un permiso concreto por su id
        echo json_encode($this->permisos_m->leerPermiso($_REQUEST['id']));
    }
    public function consmes()
    {
        if (!isset($_SESSION['sesion'])) header("location:" . BASE_URL . "Usuarios_c/login");
        // Visualizar la vista
        $contenido = "consmes_v";
        $datos['config'] = $this->config_m->leerConfig();
        $this->load_view("plantilla/cabecera");
        $this->load_view($contenido, $datos);
        $this->load_view("plantilla/pie");
    }
    public function leerTodosPorFecha()
    {
        //Ajax. Leer permisos entre dos fechas dadas
        echo json_encode($this->permisos_m->leerTodosPorFecha($_REQUEST['fdesde'], $_REQUEST['fhasta']));
    }

    public function insertar()
    {
        unset($_REQUEST['causad']); // Quitar la causa del array
        // Sacar los datos para firmar
        // Insertar permiso
        $id = $this->permisos_m->insertar($_REQUEST);

        $usuarios_m = $this->load_model("Usuarios_m");
        $datosUsuario = $usuarios_m->leer($_REQUEST['usuario']);
        // Pasar apellidos y nombre y dpto a la vista
        $_REQUEST['apenom'] = $datosUsuario['apenom'];
        $_REQUEST['departamento'] = $datosUsuario['departamento'];

        // Cargar Clase
        $path_lib = ROOT . PATH_LIBS . "/html2pdf/html2pdf.class.php";
        if (is_file($path_lib)) {
            require_once $path_lib;
        } else {
            throw new Exception("Libreria no existe");
        }

        // Obtener el HTML para crear PDF en content
        ob_start();
        include(ROOT . PATH_VIEWS . '/solicitudPermiso_pdf.php');
        $content = ob_get_clean();

        try {
            // init HTML2PDF
            $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', array(0, 0, 0, 0));
            // Visualizar página completa
            $html2pdf->pdf->SetDisplayMode('fullpage');
            // convertir
            $html2pdf->writeHTML($content, false);

            // nombre PDF
            $fichero = $id . '.pdf';
            $html2pdf->Output(ROOT . 'app/assets/documentos/' . $fichero, 'F');

            // leer configuracion
            $config = $this->config_m->leerConfig();

            // Enviar correo a Secretaria
            if (!empty($config['email_secretaria'])) $this->enviarCorreo($config['email_secretaria'], "Secretaría", "Aviso de solicitud de registro", "<h3>Hay solicitudes de permisos particulares por registrar</h3>");

            header("location:" . BASE_URL . "Permisos_c/index");
            //header("location:" . $_SERVER['HTTP_REFERER']);
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }
    private function enviarCorreo($dest, $nombre, $asunto, $mensaje, $adjunto = "", $ical = "")
    {

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->CharSet = "UTF-8";
            $mail->SMTPDebug = SMTP::DEBUG_OFF;            //Enable verbose debug output
            $mail->isSMTP();                               //Send using SMTP
            $mail->Host       = EMAIL_HOST;                //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                      //Enable SMTP authentication
            $mail->Username   = EMAIL_USER;                //SMTP username
            $mail->Password   = EMAIL_PASS;                //SMTP password
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port       = 587;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            if ($ical) $mail->Ical = $ical;
            //if ($ical) $mail->addStringAttachment($ical, "anotacion.ics");
            //Recipients
            $mail->setFrom('agora@iesagora.es', 'Solicitud permisos particulares');
            $mail->addAddress($dest, $nombre);     //Add a recipien
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            if (!empty($adjunto)) {
                $mail->addAttachment($adjunto);         //Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
            }
            $mail->AddEmbeddedImage(ROOT . "app/assets/img/logocentrop.png", 'imagen'); //ruta de archivo de imagen
            //Content
            $mail->isHTML(true);
            //Set email format to HTML
            //cargar archivo css para cuerpo de mensaje
            $rcss = ROOT . "app/assets/estilo.css"; //ruta de archivo css
            $fcss = fopen($rcss, "r"); //abrir archivo css
            $scss = fread($fcss, filesize($rcss)); //leer contenido de css
            fclose($fcss); //cerrar archivo css
            //Cargar archivo html   
            $shtml = file_get_contents(ROOT . "app/assets/correo.html");
            //reemplazar sección de plantilla html con el css cargado y mensaje creado
            $incss  = str_replace('<style id="estilo"></style>', "<style>$scss</style>", $shtml);
            $cuerpo = str_replace('<div id="mensaje"></div>', $mensaje, $incss);
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpo;

            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            error_log("El mensaje no se ha podido enviar. Error: {$mail->ErrorInfo}");
        }
    }

    public function crearApunteiCalendar($comienzo, $final)
    {
        $icalobj = new ZCiCal();

        $eventobj = new ZCiCalNode("VEVENT", $icalobj->curnode);
        // anadir titulo
        $eventobj->addNode(new ZCiCalDataNode("SUMMARY: Día de permiso particular"));
        // comienzo
        $eventobj->addNode(new ZCiCalDataNode("DTSTART:" . ZCiCal::fromSqlDateTime($comienzo)));
        // fin
        $eventobj->addNode(new ZCiCalDataNode("DTEND:" . ZCiCal::fromSqlDateTime($final)));

        // UID is a required item in VEVENT, create unique string for this event
        // Adding your domain to the end is a good way of creating uniqueness
        $uid = date('Y-m-d-H-i-s') . "@iesagora.es";
        $eventobj->addNode(new ZCiCalDataNode("UID:" . $uid));

        // DTSTAMP is a required item in VEVENT
        $eventobj->addNode(new ZCiCalDataNode("DTSTAMP:" . ZCiCal::fromSqlDateTime()));

        // Añadir descripción
        $eventobj->addNode(new ZCiCalDataNode("Description:" . ZCiCal::formatContent(
            "Visitar http://icalendar.org para validar este fichero."
        )));

        // Devolver String con icalendar
        return $icalobj->export();
    }

    public function numerarPeticiones()
    {
        // Numera la peticiones 
        $permisos = $this->permisos_m->leerTodosPermisos();
        $usuback = $permisos[0]['usuario'];
        $npeticion = 1;
        foreach ($permisos as $permi) {
            if ($permi['usuario'] != $usuback) {
                $npeticion = 1;
                $usuback = $permi['usuario'];
            }

            $permi['npeticion'] = $npeticion;
            $npeticion += 1;
            $this->permisos_m->modificar($permi);
        }
        echo "Acabado";
    }
}
