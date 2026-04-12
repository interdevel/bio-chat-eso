<?php
// chat.php - Asistente de Biología ESO con phi3:mini
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Capturamos la pregunta del estudiante
$data = json_decode(file_get_contents('php://input'), true);
$preguntaUsuario = $data['prompt'] ?? '';

if (empty(trim($preguntaUsuario))) {
    echo json_encode([
        "response" => "Por favor, escribe una pregunta sobre biología."
    ]);
    exit;
}

// System prompt específico para biología de secundaria
$systemPrompt = "Eres un profesor de biología de educación secundaria obligatoria (ESO) en España. 
Tu objetivo es ayudar a estudiantes de 12 a 16 años a comprender conceptos de biología.

INSTRUCCIONES:
- Usa un lenguaje claro y apropiado para la edad
- Da ejemplos cotidianos cuando sea posible
- Si la pregunta es muy compleja, simplifica sin perder rigor científico
- Si la pregunta no es sobre biología, redirige amablemente al tema
- Sé motivador y positivo
- Las respuestas deben ser concisas (máximo 200 palabras)

Pregunta del estudiante: ";

// Configuración de la petición a Ollama
$ch = curl_init("http://localhost:11434/api/generate");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 180);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "phi3:mini",
    "prompt" => $systemPrompt . $preguntaUsuario,
    "stream" => false,
    "options" => [
        "temperature" => 0.7,      // Balance entre creatividad y precisión
        "top_p" => 0.9,            // Diversidad de respuestas
        "num_predict" => 350,      // Límite de tokens en la respuesta
        "num_ctx" => 4096,         // Contexto (elimina el warning)
        "repeat_penalty" => 1.1    // Evita repeticiones
    ]
]));

// Ejecutamos la petición
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Manejo de errores
if ($curlError) {
    echo json_encode([
        "response" => "Error de conexión: " . $curlError . "\n\n¿Has ejecutado 'ollama serve' en otra terminal?"
    ]);
    exit;
}

if ($httpCode !== 200) {
    echo json_encode([
        "response" => "Error del servidor Ollama (código $httpCode). Verifica que Ollama esté ejecutándose con: ollama serve"
    ]);
    exit;
}

// Devolvemos la respuesta
echo $response;