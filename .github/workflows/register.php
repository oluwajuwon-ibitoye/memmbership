<?php
require 'vendor/autoload.php'; // PHPMailer & Twilio
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Twilio\Rest\Client;


// reCAPTCHA validation
$recaptcha = $_POST['g-recaptcha-response'];
$secret = 'YOUR_SECRET_KEY';
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$recaptcha");
$result = json_decode($response);

if (!$result->success) {
    die("Captcha failed. Please try again.");
}

// DB credentials
$host = "localhost";
$dbname = "membership_db";
$username = "root";
$password = "";

// Twilio credentials
$twilio_sid = "YOUR_TWILIO_SID";
$twilio_token = "YOUR_TWILIO_AUTH_TOKEN";
$twilio_phone = "YOUR_TWILIO_PHONE_NUMBER";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = htmlspecialchars($_POST["fullname"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $stmt = $pdo->prepare("INSERT INTO members (fullname, email, phone) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $phone]);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }

    // PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ibitoyeoluwajuwon@gmail.com';
        $mail->Password = 'kgbt lsps xonu xbbd';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('ibitoyeoluwajuwon@gmail.com', 'Oluwajuwon');
        $mail->addAddress($email, $name);
        $mail->Subject = 'Membership Confirmation';
        $mail->Body = "Hi $name,\n\nThank you for registering as a member!\n\n- The Team";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
    }

    // Twilio SMS
    try {
        $client = new Client($twilio_sid, $twilio_token);
        $client->messages->create(
            $phone,
            [
                'from' => $twilio_phone,
                'body' => "Hi $name, thanks for registering! You've successfully joined our community."
            ]
        );
    } catch (Exception $e) {
        error_log("SMS not sent: " . $e->getMessage());
    }

    header("Location: thankyou.html");
    exit();
}
?>