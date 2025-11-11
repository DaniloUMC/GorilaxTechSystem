<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../vendor/autoload.php';




function enviarEmail($email, $nome ,$dados){
    $mail = new PHPMailer(true);

    try {
        // Configuração do servidor
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'daniloarfe@gmail.com'; // Seu e-mail Hotmail/Outlook
        $mail->Password   = 'taeo zhym vzuf owob';            // Sua senha normal (ou senha de app se tiver 2FA)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;


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
