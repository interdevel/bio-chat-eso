<?php
// chat-groq-rag.php - Con contexto de libros de texto (RAG básico)
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
    echo json_encode(["response" => "Por favor, escribe una pregunta sobre biología."]);
    exit;
}

// ========================================
// BASE DE CONOCIMIENTO VERIFICADA
// Aquí tu hijo puede añadir contenido de sus libros de texto
// ========================================
$archivoConocimiento = __DIR__ . '/conocimiento.json';
$baseConocimiento = [];

if (file_exists($archivoConocimiento)) {
    $contenidoJson = file_get_contents($archivoConocimiento);
    $baseConocimiento = json_decode($contenidoJson, true) ?: [];
} else {
    echo json_encode(["response" => "⚠️ Error: Archivo conocimiento.json no encontrado."]);
    exit;
}

// ========================================
// BÚSQUEDA DE CONTEXTO RELEVANTE
// ========================================
function buscarContextoRelevante($pregunta, $baseConocimiento) {
    // Función simple para quitar acentos y pasar a minúsculas
    $reemplazos = ['á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'Á'=>'a', 'É'=>'e', 'Í'=>'i', 'Ó'=>'o', 'Ú'=>'u', 'ñ'=>'n', 'Ñ'=>'n', '?'=>'', '¿'=>''];
    $preguntaLower = strtolower(strtr($pregunta, $reemplazos));
    
    $contextosRelevantes = [];
    
    // Cargar Sinónimos básicos de archivo externo (Fase 1 y 2 del Roadmap completadas)
    $archivoSinonimos = __DIR__ . '/sinonimos.json';
    $sinonimos = file_exists($archivoSinonimos) 
        ? json_decode(file_get_contents($archivoSinonimos), true) ?: []
        : [];
    
    $palabrasPregunta = str_word_count($preguntaLower, 1);
    
    foreach ($baseConocimiento as $temaOriginal => $contenido) {
        $tema = strtolower(strtr($temaOriginal, $reemplazos));
        
        // 1. Coincidencia exacta o parcial en la pregunta
        if (strpos($preguntaLower, $tema) !== false) {
            $contextosRelevantes[$temaOriginal] = $contenido;
            continue;
        }
        
        // 2. Coincidencia por sinónimos
        if (isset($sinonimos[$tema])) {
            foreach ($sinonimos[$tema] as $sinonimo) {
                if (strpos($preguntaLower, $sinonimo) !== false) {
                    $contextosRelevantes[$temaOriginal] = $contenido;
                    continue 2;
                }
            }
        }
        
        // 3. Tolerancia a errores ortográficos (Distancia Levenshtein)
        foreach ($palabrasPregunta as $palabra) {
            if (strlen($palabra) > 4 && levenshtein($palabra, $tema) <= 2) {
                $contextosRelevantes[$temaOriginal] = $contenido;
                break;
            }
        }
    }
    
    $resultadosUnicos = array_values($contextosRelevantes);
    return implode("\n\n", array_slice($resultadosUnicos, 0, 2)); // Máximo 2 contextos
}

$contextoRelevante = buscarContextoRelevante($preguntaUsuario, $baseConocimiento);

// ========================================
// SYSTEM PROMPT CON CONTEXTO INYECTADO
// ========================================
$systemPrompt = "Eres un profesor de biología de ESO en España. Tu trabajo es ayudar a estudiantes de 12-16 años.

REGLAS CRÍTICAS:
1. SOLO usa información de la BASE DE CONOCIMIENTO proporcionada
2. Si la base NO contiene la respuesta, di: \"No tengo información verificada sobre esto. Consulta tu libro de texto o pregunta a tu profesor\"
3. NUNCA inventes datos, nombres científicos o cifras
4. Si hay contexto relevante abajo, úsalo TEXTUALMENTE
5. Adapta el lenguaje al nivel ESO (simplifica sin perder rigor)
6. Máximo 150 palabras

" . ($contextoRelevante ? "BASE DE CONOCIMIENTO VERIFICADA:\n$contextoRelevante" : "No hay contexto específico para esta pregunta en la base de conocimiento.");

// ========================================
// LLAMADA A API CON TEMPERATURA MUY BAJA
// ========================================
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
    "temperature" => 0.1,  // ⬇️⬇️ MUY BAJA para máxima fidelidad al contexto
    "top_p" => 0.85,
    "max_tokens" => 300
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo json_encode(["response" => "Error de API. Verifica tu API key."]);
    exit;
}

$apiResponse = json_decode($response, true);
$respuestaIA = $apiResponse['choices'][0]['message']['content'] ?? 'Error al procesar';

// Añadir nota si se usó contexto
if ($contextoRelevante) {
    $respuestaIA .= "\n\n✅ Información verificada desde base de conocimiento del libro de texto.";
}

echo json_encode([
    "response" => $respuestaIA,
    "contexto_usado" => !empty($contextoRelevante)
]);
