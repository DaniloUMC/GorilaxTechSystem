<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../vendor/autoload.php';




function enviarEmail($email, $nome ,$dados){
    $mail = new PHPMailer(true);

    try {
        // Configuração do servidor
        

        //secrets


        // Remetente e destinatário
        $mail->setFrom('daniloarfe@gamil.com', 'GorilaxTech');
        $mail->addAddress($email, $nome);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'GorilaxTech';
        $mail->Body    = $dados;
        $mail->AltBody = 'Agradecemos a confiança.';

        $mail->send();

    } catch (Exception $e) {
        echo "❌ Erro ao enviar: {$mail->ErrorInfo}";
        exit();
    }
}
