<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('Data');
$log->pushHandler(new StreamHandler('info.log', Level::Warning));




$mail = new PHPMailer(true);



$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV["HOST"];
$username = $_ENV["USERNAME"];
$password = $_ENV["PASSWORD"];
$name = $_ENV["NAME"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        //hosting gegevens, email password etc 
        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $username;
        $mail->Password   = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //php van het ophalen van de gegevens die je invult
        $naam = $_POST["naam"];
        $email = $_POST["email"];
        $klacht = $_POST["klacht"];

        //gegevens van de gebruiker die het verzendt
        $mail->setFrom($username, $name);
        $mail->addAddress($email, $naam); 
        $mail->addReplyTo($email, $naam);
        $mail->isHTML(true);
        //onderwerp
        $mail->Subject = 'Klacht van ' . $naam;
        //overige
        $mail->Body    = 'Gedag' . ' ' . $naam . '<br>' . '<br>' . 
        'Hierbij heb ik uw klacht ontvangen in goede orde' . '<br>' . 'Ik reageer spoedig op uw klacht :' . $klacht . '<br>' . 'Met vriendelijke groet,' . '<br>' . 'Farhan Mohammed.';
        
        $mail->AltBody = 'Ik kom hierop terug'; 
        $mail->send();
        echo 'Bericht is verzonden';
    } catch (Exception $e) {
        echo "Het bericht kon niet worden verzonden. Mailerfout: {$mail->ErrorInfo}";
    }
    
$log->warning("Name: {$naam}, Email: {$email}, Message: {$klacht}");
$log->error('Error');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactformulier</title>
</head>
<body>
    <form method="post" action="">
        <input type="text" name="naam" placeholder="Naam">
        <input type="email" name="email" placeholder="Email">
        <input type="text" name="klacht" placeholder="klacht">
        <input type="submit" value="Verzenden">
    </form>
</body>
</html>
