<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Contactos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }
    public function index()
    {
        if (isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['mensaje'])) {
            if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['mensaje'])) {
                $mensaje = array('msg' => 'TODO LOS CAMPOS SON REQUERIDOS', 'icono' => 'warning');
            } else {
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    $mail->SMTPDebug = 0;                      
                    $mail->isSMTP();                                           
                    $mail->Host       = HOST_SMTP;                     
                    $mail->SMTPAuth   = true;                                
                    $mail->Username   = USER_SMTP;                    
                    $mail->Password   = PASS_SMTP;                              
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
                    $mail->Port       = PUERTO_SMTP;                                    

                    //Recipients
                    $mail->setFrom('josiascontrerasm@gmail.com', TITLE);
                    $mail->addAddress($_POST['email']);

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = $_POST['nombre'] . ' Mensaje desde la: ' . TITLE;
                    $mail->Body    = $_POST['mensaje'];
                    $mail->AltBody = 'GRACIAS POR LA PREFERENCIA';

                    $mail->send();
                    $mensaje = array('msg' => 'CORREO ENVIADO, REVISA TU BANDEJA DE ENTRADA - SPAN', 'icono' => 'success');
                } catch (Exception $e) {
                    $mensaje = array('msg' => 'ERROR AL ENVIAR CORREO: ' . $mail->ErrorInfo, 'icono' => 'error');
                }
            }
        } else {
            $mensaje = array('msg' => 'ERROR FATAL: ', 'icono' => 'error');
        }
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        die();
    }
}
