<?php
// Inclure les fichiers nécessaires de PHPMailer avec le bon chemin
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Utilisation des classes PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Variable pour le message de confirmation
$confirmationMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $size = htmlspecialchars($_POST['size']);
    $duration = htmlspecialchars($_POST['duration']);
    $location = htmlspecialchars($_POST['location']);
    $email = htmlspecialchars($_POST['email']);

   // Configurer PHPMailer
   $mail = new PHPMailer(true);

   try {
       // Paramètres du serveur SMTP
       $mail->isSMTP();
       $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
       $mail->SMTPAuth = true;
       $mail->Username = 'speedbennes@gmail.com'; // Ton adresse email Gmail
       $mail->Password = 'ssij ysag kglx srfx'; // Ton mot de passe d'application Gmail
       $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Utiliser STARTTLS pour sécuriser la connexion
       $mail->Port = 587; // Port (587 pour STARTTLS, 465 pour SMTPS)

       // Configuration de l'expéditeur
       $mail->setFrom('speedbennes@gmail.com', 'Speedbenne'); // Adresse et nom de l'agence
       $mail->addAddress('speedbennes@gmail.com', 'Agence Location Benne'); // Adresse email de l'agence (destinataire)
       $mail->addReplyTo($email, 'Client'); // L'adresse à laquelle répondre (celle de l'utilisateur qui remplit le formulaire)

        // Corps du message
        $mail->isHTML(false);
        $mail->Subject = 'Demande de devis';
        $mail->Body = "Taille de la Benne: $size\nDurée de location: $duration jours\nLieu de livraison: $location\nEmail: $email";

        // Envoi de l'email
        if ($mail->send()) {
            $confirmationMessage = "Votre demande a bien été envoyée !";
        } else {
            $confirmationMessage = "Une erreur est survenue, veuillez réessayer.";
        }
    } catch (Exception $e) {
        $confirmationMessage = "Erreur : {$mail->ErrorInfo}";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de devis</title>
    <style>
        .confirmation-message {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            z-index: 1000;
        }

        #close-button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- Fenêtre de confirmation -->
<?php if ($confirmationMessage): ?>
    <div id="confirmation" class="confirmation-message">
        <p><?php echo $confirmationMessage; ?></p>
        <button id="close-button">Fermer</button>
    </div>
<?php endif; ?>

<script>
    // Affichage de la fenêtre de confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const confirmation = document.getElementById('confirmation');
        const closeButton = document.getElementById('close-button');

        if (confirmation) {
            // Affiche la fenêtre de confirmation
            confirmation.style.display = 'block';

            // Ferme la fenêtre automatiquement après 5 secondes
            setTimeout(function() {
                confirmation.style.display = 'none';
            }, 5000);

            // Permet de fermer la fenêtre manuellement
            closeButton.addEventListener('click', function() {
                confirmation.style.display = 'none';
            });
        }
    });
</script>

</body>
</html>
