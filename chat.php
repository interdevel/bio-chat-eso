<?php
// chat.php - El puente entre tu hijo y la IA
header('Content-Type: application/json');

// Capturamos la pregunta que viene del Frontend
$data = json_decode(file_get_contents('php://input'), true);
$preguntaUsuario = $data['prompt'] ?? '¿Qué es una célula?';

// Configuramos la petición a Ollama
$ch = curl_init("http://localhost:11434/api/generate");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "gemma:2b", // Asegúrate de haber hecho 'ollama run <nombre del modelo>'
    "prompt" => "Responde como un profesor de biología de secundaria: " . $preguntaUsuario,
    "stream" => false // Para que nos devuelva la respuesta de golpe
]));

$response = curl_exec($ch);
curl_close($ch);

// Enviamos la respuesta de vuelta al navegador
echo $response;
