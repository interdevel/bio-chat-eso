<?php
// chat-groq-rag.php - Con contexto de libros de texto (RAG básico)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$GROQ_API_KEY = "TU_API_KEY_AQUI"; // ⚠️ REEMPLAZA ESTO

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
$baseConocimiento = [
    "fotosintesis" => "La fotosíntesis es el proceso mediante el cual las plantas producen su propio alimento usando luz solar, dióxido de carbono (CO₂) del aire y agua (H₂O). Ocurre principalmente en los cloroplastos de las células vegetales. La fórmula general es: 6CO₂ + 6H₂O + luz → C₆H₁₂O₆ + 6O₂. Este proceso libera oxígeno (O₂) como producto. Las plantas son organismos autótrofos porque fabrican su alimento. La fotosíntesis tiene dos fases: luminosa (necesita luz) y oscura (no necesita luz directa).",
    
    "celula" => "La célula es la unidad básica de la vida. Todos los seres vivos están formados por células. Existen dos tipos principales: células procariotas (sin núcleo definido, como las bacterias) y células eucariotas (con núcleo, como animales y plantas). Las partes básicas de una célula son: membrana plasmática (protege y controla entrada/salida), citoplasma (líquido interno), núcleo (contiene ADN), mitocondrias (producen energía) y en plantas también cloroplastos (fotosíntesis) y pared celular (soporte).",
    
    "adn" => "El ADN (ácido desoxirribonucleico) es la molécula que contiene toda la información genética de un ser vivo. Tiene forma de doble hélice (como una escalera en espiral). Está formado por cuatro bases nitrogenadas: Adenina (A), Timina (T), Citosina (C) y Guanina (G). Las bases se emparejan siempre igual: A con T, y C con G. El ADN se encuentra en el núcleo de las células y contiene los genes, que son las instrucciones para fabricar proteínas. Fue descubierto su estructura por Watson y Crick en 1953.",
    
    "mitosis" => "La mitosis es el proceso de división celular que produce dos células hijas idénticas a la célula madre. Tiene cuatro fases: Profase (los cromosomas se hacen visibles), Metafase (cromosomas se alinean en el centro), Anafase (cromosomas se separan) y Telofase (se forman dos núcleos). Antes de la mitosis ocurre la interfase, donde la célula duplica su ADN. La mitosis es fundamental para el crecimiento y reparación de tejidos.",
    
    "ecosistema" => "Un ecosistema es el conjunto de seres vivos (factores bióticos) que habitan en un lugar determinado y su relación con el medio físico (factores abióticos: agua, temperatura, luz, suelo). Los componentes de un ecosistema son: productores (plantas que hacen fotosíntesis), consumidores (herbívoros, carnívoros, omnívoros) y descomponedores (hongos y bacterias que reciclan materia). La energía fluye en cadenas tróficas desde productores hasta descomponedores.",
    
    "genetica" => "La genética es la ciencia que estudia la herencia biológica. Los caracteres heredables se transmiten mediante genes. Cada individuo hereda la mitad de sus genes de cada progenitor. Gregor Mendel es el padre de la genética moderna (leyes de Mendel). Los genes pueden ser dominantes (se manifiestan siempre) o recesivos (solo se manifiestan si hay dos copias). El genotipo es la información genética y el fenotipo es cómo se manifiesta (aspecto observable)."
];

// ========================================
// BÚSQUEDA DE CONTEXTO RELEVANTE
// ========================================
function buscarContextoRelevante($pregunta, $baseConocimiento) {
    $preguntaLower = strtolower($pregunta);
    $contextosRelevantes = [];
    
    foreach ($baseConocimiento as $tema => $contenido) {
        if (strpos($preguntaLower, $tema) !== false) {
            $contextosRelevantes[] = $contenido;
        }
    }
    
    // Búsqueda adicional por palabras clave
    $palabrasClave = ['célula', 'celula', 'adn', 'gen', 'cromosoma', 'mitosis', 'meiosis', 
                      'fotosíntesis', 'fotosintesis', 'cloroplasto', 'ecosistema', 'cadena'];
    
    foreach ($palabrasClave as $palabra) {
        if (strpos($preguntaLower, $palabra) !== false) {
            foreach ($baseConocimiento as $tema => $contenido) {
                if (strpos(strtolower($contenido), $palabra) !== false && 
                    !in_array($contenido, $contextosRelevantes)) {
                    $contextosRelevantes[] = $contenido;
                }
            }
        }
    }
    
    return implode("\n\n", array_slice($contextosRelevantes, 0, 2)); // Máximo 2 contextos
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
