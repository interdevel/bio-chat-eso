<?php
// chat-groq.php - Versión con API externa (sin necesidad de Ollama local)
header('Content-Type: application/json');

// Cargar configuración (crea un archivo config.php local y NO lo subas a GitHub)
if (file_exists('config.php')) {
    include 'config.php';
}
require __DIR__ . '/request-helpers.php';

apply_cors();
require_post();

// API key obtenida del entorno o config
$GROQ_API_KEY = defined('GROQ_API_KEY') ? GROQ_API_KEY : getenv('GROQ_API_KEY');

if (!$GROQ_API_KEY) {
    echo json_encode(["response" => "Error de configuración: API Key no definida."]);
    exit;
}

$preguntaUsuario = read_prompt();

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
    // Log del error real
    error_log("Groq Basic API Error ($httpCode): " . $response);
    echo json_encode([
        "response" => "Ocurrió un error al procesar tu pregunta. El administrador ha sido notificado."
    ]);
    exit;
}

$data = json_decode($response, true);
echo json_encode([
    "response" => $data['choices'][0]['message']['content'] ?? "Error al procesar respuesta"
]);
