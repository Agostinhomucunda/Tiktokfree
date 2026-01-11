<?php
header('Content-Type: application/json');

// ===== CONFIGURAÇÕES =====
$api_key = '616d2690f7d47890968bb794a1beb54e';
$api_url = 'https://justanotherpanel.com/api/v2';
$service_id = 8610;
$max_requests = 2;
$cooldown = 86400; // 24 horas
$file = 'ips.json';

// ===== IP DO USUÁRIO =====
$ip = $_SERVER['REMOTE_ADDR'];
$now = time();

// ===== LÊ O ARQUIVO =====
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}
$data = json_decode(file_get_contents($file), true);

// ===== VERIFICA LIMITE DIÁRIO =====
if (isset($data[$ip])) {
    if (($now - $data[$ip]['time']) < $cooldown && $data[$ip]['count'] >= $max_requests) {
        echo json_encode([
            'error' => 'Limite diário atingido. Volte amanhã.'
        ]);
        exit;
    }

    // Reseta após 24h
    if (($now - $data[$ip]['time']) >= $cooldown) {
        $data[$ip] = ['count' => 0, 'time' => $now];
    }
} else {
    $data[$ip] = ['count' => 0, 'time' => $now];
}

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
    'key' => $api_key,
    'action' => 'add',
    'service' => $service_id,
    'link' => 'https://www.tiktok.com/@' . $username,
    'quantity' => $quantity
];

// ===== ENVIO =====
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$response = curl_exec($ch);
curl_close($ch);

// ===== ATUALIZA IP =====
$data[$ip]['count']++;
file_put_contents($file, json_encode($data));

// ===== RESPOSTA =====
echo $response;