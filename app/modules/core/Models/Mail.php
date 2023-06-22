<?php

/**
* Modelo del modulo Core que se encarga de inicializar  la clase de envio de correos
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Core_Model_Mail
{
    /**
     * classe de  phpmailer
     * @var class
     */
    private $mail;

    /**
     * asigna los valores a la clase e instancia el phpMailer
     */
    public function __construct()
    {
        
        $informacionModel = new Page_Model_DbTable_Informacion();
        $informacion = $informacionModel->getList("","orden ASC")[0];

        $this->mail = new PHPMailer;
        $this->mail->CharSet = 'UTF-8';
         $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Host = "mail.tacticaspanama.com";
        $this->mail->Port = 465;
        $this->mail->SMTPAuth = true;
        $this->mail->Username ="sistemanomina@tacticaspanama.com";
        $this->mail->Password = "Admin.2008*";
        $this->mail->setFrom("sistemanomina@tacticaspanama.com", "Sistema de nómina");


    }
    /**
     * retorna la  instancia de email
     * @return class email
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * envia el correo
     * @return bool envia el estado del correo
     */
    public function sed()
    {
        if ($this->mail->send()) {
            return true;
        } else {
            return false;
        }
    }
}