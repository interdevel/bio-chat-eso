<?php
// chat-groq.php - Versión optimizada para educación con anti-alucinaciones
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// IMPORTANTE: Obtén tu API key gratis en: https://console.groq.com
$GROQ_API_KEY = "TU_API_KEY_AQUI"; // ⚠️ REEMPLAZA ESTO

$data = json_decode(file_get_contents('php://input'), true);
$preguntaUsuario = $data['prompt'] ?? '';

if (empty(trim($preguntaUsuario))) {
    echo json_encode([
        "response" => "Por favor, escribe una pregunta sobre biología."
    ]);
    exit;
}

// System prompt robusto con contexto curricular ESO y técnicas anti-alucinación
$systemPrompt = "Eres un profesor de biología de educación secundaria obligatoria (ESO) en España.

CONTEXTO CURRICULAR ESO - BIOLOGÍA Y GEOLOGÍA:
Los estudiantes estudian estos temas según el curso:
- 1º ESO (12-13 años): Seres vivos y clasificación, célula, nutrición, reproducción, ecosistemas
- 2º ESO (13-14 años): Nutrición humana, salud, aparatos y sistemas del cuerpo
- 3º ESO (14-15 años): Organización del ser humano, salud y enfermedad, genética básica
- 4º ESO (15-16 años): Evolución, genética avanzada, ADN, ecología, geología

INSTRUCCIONES ESTRICTAS ANTI-ALUCINACIÓN:
1. NUNCA inventes datos, fechas, nombres científicos o cifras. Si no estás 100% seguro, di \"No tengo esa información específica\".
2. Para preguntas fuera de biología/geología de ESO, redirige: \"Esta pregunta está fuera del temario de biología de ESO. ¿Puedo ayudarte con otro tema de biología?\"
3. Si la pregunta es ambigua, pide aclaración antes de responder.
4. Usa SOLO conocimiento científico consensuado y verificado.
5. Si hay controversia científica, menciona que es un tema de debate activo.
6. Evita tecnicismos innecesarios. Prioriza claridad sobre precisión extrema.
7. NO des consejos médicos personales. Di: \"Para temas de salud personal, consulta con un profesional médico\".

ESTRUCTURA DE RESPUESTA:
- Concepto principal (2-3 frases)
- Ejemplo cotidiano o analogía (1-2 frases)
- Relación con temario ESO si aplica
- Máximo 150 palabras

TONO: Cercano, motivador, educativo. Nunca condescendiente.

VALIDACIÓN: Antes de responder, pregúntate: \"¿Esto es 100% verificable en libros de texto de ESO?\" Si no, reformula o admite limitación.";

// Llamada a Groq API con parámetros optimizados para educación
$ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $GROQ_API_KEY
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "llama-3.3-70b-versatile",
    "messages" => [
        ["role" => "system", "content" => $systemPrompt],
        ["role" => "user", "content" => $preguntaUsuario]
    ],
    "temperature" => 0.3,  // ⬇️ Temperatura BAJA para reducir creatividad/alucinaciones
    "top_p" => 0.9,        // Enfoca en respuestas más probables
    "max_tokens" => 350,   // Respuestas concisas
    "frequency_penalty" => 0.2,  // Reduce repeticiones
    "presence_penalty" => 0.1    // Fomenta información nueva pero controlada
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Manejo de errores
if ($curlError) {
    echo json_encode([
        "response" => "Error de conexión con Groq API. Verifica tu conexión a internet."
    ]);
    exit;
}

if ($httpCode !== 200) {
    $errorData = json_decode($response, true);
    $errorMsg = $errorData['error']['message'] ?? 'Error desconocido';
    
    echo json_encode([
        "response" => "Error de API (código $httpCode): $errorMsg. Verifica tu API key en chat-groq.php"
    ]);
    exit;
}

$apiResponse = json_decode($response, true);
$respuestaIA = $apiResponse['choices'][0]['message']['content'] ?? '';

// VALIDACIÓN POST-PROCESAMIENTO: Detectar posibles alucinaciones
$palabrasSospechosas = [
    'según estudios recientes',
    'investigaciones demuestran',
    'científicos descubrieron recientemente',
    'en 20[0-9]{2}', // Años específicos sin contexto
    'un estudio de',
    'la universidad de'
];

$hayAlerta = false;
foreach ($palabrasSospechosas as $patron) {
    if (preg_match('/' . $patron . '/i', $respuestaIA)) {
        $hayAlerta = true;
        break;
    }
}

// Si detectamos posible alucinación, añadimos disclaimer
if ($hayAlerta) {
    $respuestaIA .= "\n\n⚠️ Nota: Esta respuesta menciona estudios específicos. Verifica esta información con tu profesor o libro de texto.";
}

echo json_encode([
    "response" => $respuestaIA,
    "model_used" => "llama-3.3-70b-versatile",
    "temperature" => 0.3  // Info útil para debugging
]);
