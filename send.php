<?php
header('Content-Type: application/json');

// ===== CONFIGURAÇÕES =====
$api_key = '616d2690f7d47890968bb794a1beb54e';
$api_url = 'https://justanotherpanel.com/api/v2';
$service_id = 8610;

// ===== MÉTODO =====
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// ===== ANTI BOT =====
$captcha = intval($_POST['captcha'] ?? 0);
$captcha_answer = intval($_POST['captcha_answer'] ?? 0);

if ($captcha !== $captcha_answer) {
    echo json_encode(['error' => 'Captcha incorreto']);
    exit;
}

// ===== USUÁRIO =====
$username = trim($_POST['username'] ?? '');
if (empty($username)) {
    echo json_encode(['error' => 'Usuário inválido']);
    exit;
}

// ===== QUANTIDADE ALEATÓRIA =====
$quantidades = [15, 20, 30];
$quantity = $quantidades[array_rand($quantidades)];

// ===== DADOS PARA API =====
$post_data = [
    'key'     => $api_key,
    'action'  => 'add',
    'service' => $service_id,
    'link'    => 'https://www.tiktok.com/@' . $username,
    'quantity'=> $quantity
];

// ===== ENVIO =====
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$response = curl_exec($ch);
curl_close($ch);

// ===== RESPOSTA =====
echo $response;
