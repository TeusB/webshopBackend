<?php

namespace main;

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;
use Exception;
use Dotenv\Dotenv;
use main\Error;

class Mailer
{
    //Create an instance; passing `true` enables exceptions

    private object $error;

    public function __construct(string $emailAdress, string $name,  string $subject, string $content)
    {
        try {
            $this->error = new Error("database");

            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->Mailer = "smtp";


            $absolute = __DIR__ . '/../';
            $dotenv = Dotenv::createImmutable($absolute);
            $dotenv->load();

            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            $mail->Host = "smtp.gmail.com";
            $mail->Username = $_ENV["gmailEmail"];
            $mail->Password = $_ENV["gmailGeneratedKeyPW"];

            //Recipients
            $mail->IsHTML(true);
            $mail->AddAddress($emailAdress, $name);
            $mail->SetFrom($_ENV["gmailEmail"], $_ENV["AdminName"]);
            $mail->Subject = $subject;

            //Content
            $mail->MsgHTML($content);
            $mail->Send();
        } catch (Exception $e) {
            $this->error->log->error($e->getMessage());
            $this->error->maakError("server down couldnt send email");
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
