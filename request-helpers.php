<?php
// request-helpers.php - Shared request hardening for all chat endpoints.
//
// Implements:
//   - apply_cors():  origin allowlist (define ALLOWED_ORIGINS in config.php)
//   - require_post(): reject non-POST methods
//   - read_prompt(): validate JSON body and the "prompt" field

if (!function_exists('apply_cors')) {
    /**
     * Reflect the Origin header only if it is in the allowlist.
     * Without ALLOWED_ORIGINS defined, no CORS header is sent and only
     * same-origin requests (e.g. the bundled index.html) work.
     */
    function apply_cors() {
        $allowed = defined('ALLOWED_ORIGINS') ? ALLOWED_ORIGINS : [];
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        if ($origin !== '' && is_array($allowed) && in_array($origin, $allowed, true)) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Vary: Origin');
        }
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');

        // Preflight: answer and stop.
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}

if (!function_exists('require_post')) {
    function require_post() {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            echo json_encode(["response" => "Método no permitido."]);
            exit;
        }
    }
}

if (!function_exists('read_prompt')) {
    /**
     * Parse and validate the JSON request body.
     * Returns the trimmed prompt string, or exits with a JSON error.
     */
    function read_prompt($maxLen = 1000) {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data) || !isset($data['prompt']) || !is_string($data['prompt'])) {
            http_response_code(400);
            echo json_encode(["response" => "Petición no válida."]);
            exit;
        }

        $prompt = trim($data['prompt']);

        if ($prompt === '') {
            echo json_encode(["response" => "Por favor, escribe una pregunta sobre biología."]);
            exit;
        }

        if (strlen($prompt) > $maxLen) {
            echo json_encode(["response" => "Tu pregunta es demasiado larga. Por favor, sé más conciso."]);
            exit;
        }

        return $prompt;
    }
}
