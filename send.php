<?php
// Chemin : send.php

// Ton Webhook Discord (ne PAS exposer ce fichier publiquement ou dans un repo)
$webhook_url = 'https://canary.discord.com/api/webhooks/1378744054761918465/d1nMzVNaewn7XblPTkDuqwr3D2GPp0NnmD5eKch4R7PfNg9mlCejLQ3-sDMXlPK21CuJ';

// Lire les données POST JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo 'Invalid input';
    exit;
}

// Construire le message à envoyer
$message = "**Nouvelle demande de contact :**\n\n"
         . "**Nom/Pseudo :** " . htmlspecialchars($input['name']) . "\n"
         . "**Email :** " . htmlspecialchars($input['email']) . "\n"
         . "**Sujet :** " . htmlspecialchars($input['subject']) . "\n"
         . "**Message :**\n" . htmlspecialchars($input['message']);

// Préparer la payload Discord
$payload = json_encode([
    'content' => $message
]);

// Envoyer à Discord
$options = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => $payload
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($webhook_url, false, $context);

// Vérifier la réponse
if ($response === false) {
    http_response_code(500);
    echo 'Erreur Discord';
    exit;
}

http_response_code(200);
echo 'Message envoyé';
