<?php
// chat-groq.php - Versión con API externa (sin necesidad de Ollama local)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Cargar configuración (crea un archivo config.php local y NO lo subas a GitHub)
if (file_exists('config.php')) {
    include 'config.php';
}
// API key obtenida del entorno o config
$GROQ_API_KEY = defined('GROQ_API_KEY') ? GROQ_API_KEY : getenv('GROQ_API_KEY');

if (!$GROQ_API_KEY) {
    echo json_encode(["response" => "Error de configuración: API Key no definida."]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$preguntaUsuario = $data['prompt'] ?? '';

if (empty(trim($preguntaUsuario))) {
    echo json_encode([
        "response" => "Por favor, escribe una pregunta sobre biología."
    ]);
    exit;
}

$systemPrompt = "Eres un profesor de biología de educación secundaria obligatoria (ESO) en España. 
Ayudas a estudiantes de 12-16 años a comprender conceptos de biología.
Usa lenguaje claro, da ejemplos cotidianos y sé motivador.
Respuestas concisas (máximo 200 palabras).";

// Llamada a Groq API
$ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $GROQ_API_KEY
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "llama-3.3-70b-versatile", // Modelo potente y rápido
    "messages" => [
        ["role" => "system", "content" => $systemPrompt],
        ["role" => "user", "content" => $preguntaUsuario]
    ],
    "temperature" => 0.7,
    "max_tokens" => 400
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo json_encode([
        "response" => "Error en Groq API. Verifica tu API key en chat-groq.php"
    ]);
    exit;
}

$data = json_decode($response, true);
echo json_encode([
    "response" => $data['choices'][0]['message']['content'] ?? "Error al procesar respuesta"
]);
