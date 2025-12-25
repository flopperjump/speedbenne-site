<?php
// Inclure les fichiers nécessaires de PHPMailer avec le bon chemin
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Utilisation des classes PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Activer l'affichage des erreurs pour déboguer
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifier si les données du formulaire sont soumises
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $city = htmlspecialchars($_POST['city']);
    $size = htmlspecialchars($_POST['size']);
    $description = htmlspecialchars($_POST['description']);

    // Vérification basique des champs (optionnelle)
    if (empty($name) || empty($email) || empty($phone) || empty($city) || empty($size) || empty($description)) {
        echo "<script>alert('Veuillez remplir tous les champs !'); window.history.back();</script>";
        exit();
    }

    // Configurer PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'speedbennes@gmail.com'; // Ton adresse email Gmail
        $mail->Password = 'rjov rrdl ncie dbqp'; // Ton mot de passe d'application Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Utiliser STARTTLS pour sécuriser la connexion
        $mail->Port = 587; // Port (587 pour STARTTLS, 465 pour SMTPS)

        // Configuration de l'expéditeur
        $mail->setFrom('speedbennes@gmail.com', 'Speedbenne'); // Adresse et nom de l'agence
        $mail->addAddress('speedbennes@gmail.com', 'Agence Location Benne'); // Adresse email de l'agence (destinataire)
        $mail->addReplyTo($email, $name); // L'adresse à laquelle répondre (celle de l'utilisateur qui remplit le formulaire)

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = "Demande de devis de $name";
        $mail->Body = "
            <h1>Nouvelle demande de devis</h1>
            <p><strong>Nom :</strong> $name</p>
            <p><strong>Email :</strong> $email</p>
            <p><strong>Téléphone :</strong> $phone</p>
            <p><strong>Ville :</strong> $city</p>
            <p><strong>Taille de la benne :</strong> $size</p>
            <p><strong>Description :</strong> $description</p>
        ";
        $mail->AltBody = "Nom : $name\nEmail : $email\nTéléphone : $phone\nVille : $city\nTaille de la benne : $size\nDescription : $description";

        // Envoyer l'email
        $mail->send();

        // Message de succès avec fenêtre modale et effet d'animation
        echo "
        <div id='successModal' style='
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #28a745;
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 18px;
            font-family: Arial, sans-serif;
            opacity: 0;
            animation: fadeIn 2s forwards;
        '>
            <h2>Votre demande de devis a été envoyée avec succès !</h2>
            <p>Merci de nous avoir contactés. Nous reviendrons vers vous très bientôt.</p>
            <button onclick='window.location.href = \"index.html\";' style='
                background-color: #fff;
                color: #28a745;
                padding: 10px 20px;
                font-size: 16px;
                cursor: pointer;
                border: none;
                border-radius: 5px;
                margin-top: 20px;
            '>Retour à la page d'accueil</button>
        </div>

        <script>
            // Compte à rebours pour rediriger après 5 secondes
            setTimeout(function() {
                window.location.href = 'index.html';
            }, 5000); // 5000ms = 5 secondes
        </script>

        <style>
            /* Animation pour l'apparition de la fenêtre modale */
            @keyframes fadeIn {
                0% {
                    opacity: 0;
                    transform: translate(-50%, -40%);
                }
                100% {
                    opacity: 1;
                    transform: translate(-50%, -50%);
                }
            }
        </style>
        ";
    } catch (Exception $e) {
        // Message d'erreur avec redirection
        echo "<script>alert('Erreur lors de l\'envoi de votre demande : {$mail->ErrorInfo}'); window.history.back();</script>";
    }
} else {
    // Rediriger si la méthode n'est pas POST
    header("Location: index.html");
    exit();
}
?>
