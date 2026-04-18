<?php
/**
 * Portail QR - La Main à la Pâte
 * Génère des accès QR pour les clients autorisés
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Gestion CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Rate limiting simple (fichier)
$rateLimitFile = sys_get_temp_dir() . '/lmap_portal_' . $_SERVER['REMOTE_ADDR'] . '.txt';
$rateLimitWindow = 600; // 10 minutes
$rateLimitMaxAttempts = 5;

$attempts = 0;
if (file_exists($rateLimitFile)) {
    $data = json_decode(file_get_contents($rateLimitFile), true);
    if ($data && isset($data['time']) && ($data['time'] + $rateLimitWindow) > time()) {
        $attempts = $data['count'] ?? 0;
        if ($attempts >= $rateLimitMaxAttempts) {
            http_response_code(429);
            echo json_encode(['success' => false, 'message' => 'Trop de tentatives. Réessayez dans 10 minutes.']);
            exit;
        }
    }
}

// Récupérer le body JSON
$input = json_decode(file_get_contents('php://input'), true);
$phone = isset($input['phone']) ? preg_replace('/\D/', '', $input['phone']) : '';

if (strlen($phone) !== 10 || !preg_match('/^0[67]/', $phone)) {
    incrementRateLimit($rateLimitFile, $attempts);
    echo json_encode(['success' => false, 'message' => 'Numéro invalide (format: 06xx ou 07xx)']);
    exit;
}

// Liste des utilisateurs autorisés
$authorizedUsers = [
    // Anna
    '0610454709' => [
        'name' => 'Anna',
        'whatsapp' => '33610454709', // format international sans +
        'active' => true
    ],
    // Admin (toi)
    '0618840969' => [
        'name' => 'Admin',
        'whatsapp' => '33618840969',
        'active' => true
    ],
    // Ajouter d'autres clients ici
];

if (!isset($authorizedUsers[$phone])) {
    incrementRateLimit($rateLimitFile, $attempts);
    echo json_encode(['success' => false, 'code' => 'UNAUTHORIZED', 'message' => 'Numéro non autorisé. Contactez l\'administrateur pour obtenir l\'accès.']);
    exit;
}

$user = $authorizedUsers[$phone];

if (!$user['active']) {
    incrementRateLimit($rateLimitFile, $attempts);
    echo json_encode(['success' => false, 'message' => 'Accès désactivé. Contactez l\'administrateur.']);
    exit;
}

// Générer le token
$token = bin2hex(random_bytes(16));
$expires = 3600; // 1 heure

// URL WhatsApp
$waLink = 'https://wa.me/' . preg_replace('/^0/', '33', $phone) . '?text=Bonjour%20La%20Main%20à%20la%20Pâte';

// QR Code via l'API externe (qrserver.com - gratuit, fiable)
$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($waLink);

// Réponse
$response = [
    'success' => true,
    'token' => $token,
    'user' => $user['name'],
    'wa_link' => $waLink,
    'qr_url' => $qrUrl,
    'expires' => $expires
];

echo json_encode($response);
exit;

function incrementRateLimit($file, $current) {
    $data = [
        'time' => time(),
        'count' => $current + 1
    ];
    file_put_contents($file, json_encode($data));
}
