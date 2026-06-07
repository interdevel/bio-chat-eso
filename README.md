# 🧬 BioChat ESO - Asistente de Biología con IA

Proyecto educativo de código abierto para estudiantes de ESO. Un chatbot de biología con IA que ofrece diferentes modos de funcionamiento para ajustar la precisión de las respuestas.

## 📋 Características

- ✅ **3 modos de backend** seleccionables: Básico, Mejorado (anti-alucinaciones) y RAG (máxima precisión).
- ✅ **Interfaz unificada** que permite cambiar de modo fácilmente.
- ✅ **Anti-alucinación** mediante temperatura baja y validación
- ✅ **RAG (Retrieval-Augmented Generation)** con contenido de libros de texto
- ✅ **Interfaz amigable** diseñada para estudiantes de 12-16 años
- ✅ **Código abierto** para compartir y mejorar
- ✅ **Sin costes** (API gratuita de Groq)

## 🚀 Inicio Rápido

### 1. Requisitos

- PHP 7.4+ con cURL habilitado
- Navegador web moderno
- Cuenta gratuita en [Groq](https://console.groq.com)

### 2. Obtener API Key

1. Crea una cuenta gratuita en https://console.groq.com
2. Ve a "API Keys" y crea una nueva
3. Copia la API key

### 3. Configuración

1.  Copia `config.php.example` a `config.php` en la raíz del proyecto.
2.  Abre `config.php` y pega tu API key de Groq.

```php
// En tu archivo config.php (¡NO LO SUBAS A GIT! Está en .gitignore)
define('GROQ_API_KEY', 'gsk_tu_api_key_aqui');

// Allowlist de orígenes (CORS). Vacío = solo mismo origen (la propia index.html).
// Añade tu dominio aquí solo si vas a llamar a los endpoints desde otra web.
define('ALLOWED_ORIGINS', [
    // 'https://tudominio.com',
]);
```

No hay que editar las claves dentro de los archivos `chat-*.php`: todos leen la
configuración desde `config.php` (o desde la variable de entorno `GROQ_API_KEY`).

### 4. Ejecutar

```bash
# En la carpeta del proyecto
php -S localhost:8000

# Abre en el navegador
http://localhost:8000/index.html
```

`index.html` incluye un selector para cambiar entre los tres backends en caliente.

## 📚 Versiones Disponibles

### 🚀 Versión Básica (`chat-groq.php`)

**Cuándo usar:** Pruebas rápidas, preguntas generales

**Características:**
- Configuración mínima
- Respuestas rápidas
- ⚠️ Puede tener imprecisiones ocasionales

**Backend:** `chat-groq.php` — selecciónalo en el desplegable de `index.html`

---

### ⭐ Versión Mejorada (`chat-groq-mejorado.php`) - **RECOMENDADA**

**Cuándo usar:** Uso educativo general

**Características:**
- ✅ Temperatura baja (0.3) para mayor precisión
- ✅ System prompt con contexto curricular ESO
- ✅ Validación post-procesamiento
- ✅ Detección de posibles alucinaciones
- ✅ Instrucciones anti-invención de datos

**Backend:** `chat-groq-mejorado.php` — selecciónalo en el desplegable de `index.html`

**Técnicas implementadas:**
- Instrucciones explícitas de "no inventar"
- Contexto del temario ESO por curso
- Detección de frases sospechosas ("según estudios recientes...")
- Disclaimers automáticos si detecta posibles alucinaciones

---

### 🛡️ Versión RAG (`chat-groq-rag.php`) - **MÁS SEGURA**

**Cuándo usar:** Máxima precisión requerida, exámenes, evaluaciones

**Características:**
- ✅ Base de conocimiento verificada de libros de texto
- ✅ Temperatura ultra-baja (0.1)
- ✅ Respuestas basadas SOLO en contenido proporcionado
- ✅ Indica cuando usa información verificada
- ✅ Admite cuando no tiene información

**Backend:** `chat-groq-rag.php` — selecciónalo en el desplegable de `index.html`

**Cómo añadir contenido:** Ver `GUIA_CONTENIDO_VERIFICADO.md`

## 🎯 Comparativa de Versiones

| Característica | Básica | Mejorada | RAG |
|----------------|--------|----------|-----|
| **Precisión** | ⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| **Facilidad** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| **Configuración** | Mínima | Mínima | Media |
| **Temperatura** | 0.7 | 0.3 | 0.1 |
| **Validación** | ❌ | ✅ | ✅✅ |
| **Base datos** | ❌ | ❌ | ✅ |
| **Uso en aula** | ❌ | ✅ | ✅✅ |

## 🔧 Técnicas Anti-Alucinación Implementadas

### 1. Temperatura Baja
```php
"temperature" => 0.3  // Mejorada
"temperature" => 0.1  // RAG
```
Reduce la "creatividad" del modelo = menos invenciones

### 2. System Prompt Específico
```php
"NUNCA inventes datos, fechas, nombres científicos o cifras"
"Si no estás 100% seguro, di 'No tengo esa información'"
```

### 3. Contexto Curricular
```php
"1º ESO (12-13 años): Seres vivos, célula, nutrición..."
"2º ESO (13-14 años): Nutrición humana, aparatos..."
```

### 4. Validación Post-Procesamiento
```php
$palabrasSospechosas = [
    'según estudios recientes',
    'investigaciones demuestran',
    'en 20[0-9]{2}' // Fechas específicas sin contexto
];
```

### 5. RAG - Contenido Verificado
```php
$baseConocimiento = [
    "fotosintesis" => "Contenido textual del libro..."
];
```

## 📖 Añadir Contenido Verificado (solo versión RAG)

1. **Lee tu libro de texto** sobre un tema
2. **Haz un resumen** de 150-200 palabras con datos precisos
3. **Añádelo** al archivo `conocimiento.json`:

```json
{
    // ... contenido existente ...
    
    "tu_tema" => "Tu resumen verificado del libro de texto aquí..."
}
```

4. **Prueba** haciendo preguntas sobre ese tema

Ver guía completa: `GUIA_CONTENIDO_VERIFICADO.md`

## 🎓 Temas Sugeridos por Curso

### 1º ESO
- Clasificación seres vivos (5 reinos)
- Célula y sus partes
- Funciones vitales
- Ecosistemas y cadenas tróficas

### 2º ESO
- Aparato digestivo
- Aparato respiratorio
- Aparato circulatorio
- Sistema nervioso

### 3º ESO
- Organización celular
- Sistema inmunitario
- Genética mendeliana
- Salud y enfermedad

### 4º ESO
- Estructura ADN
- Evolución y selección natural
- Biotecnología
- Dinámica de ecosistemas

## ⚠️ Limitaciones y Precauciones

### Para Estudiantes
- ✅ Usa BioChat como **complemento** de estudio
- ✅ **Verifica** información importante con tu libro o profesor
- ❌ **NO copies** respuestas directamente en trabajos
- ❌ **NO sustituye** el estudio ni la comprensión

### Para Profesores
- ✅ Versión RAG recomendada para uso en aula
- ✅ Revisar respuestas antes de usarlas en evaluaciones
- ✅ Animar a estudiantes a contrastar información
- ⚠️ La IA puede equivocarse ocasionalmente

### Aspectos Técnicos
- Groq tiene límites de uso gratuito (generosos pero existen)
- Requiere conexión a internet
- Las respuestas dependen de la calidad del contenido RAG añadido

### Seguridad
- La API key se carga desde `config.php` (en `.gitignore`) o variable de entorno. Nunca la subas al repositorio.
- Los endpoints aplican allowlist de orígenes (`ALLOWED_ORIGINS`), exigen método POST y validan la entrada. Ver `request-helpers.php`.
- ⚠️ Sin límite de peticiones por IP (rate limiting): si expones los endpoints públicamente, configúralo a nivel de servidor/proxy para reducir el riesgo de abuso de tu cuota Groq.

## 🛠️ Solución de Problemas

### Error: "timeout after 60 seconds"
**Problema:** Ollama tarda mucho en cargar el modelo
**Solución:** Usar Groq API (archivos chat-groq-*.php)

### Error: "Error de configuración: API Key no definida"
**Problema:** API key incorrecta o no configurada
**Solución:** Revisa que `config.php` existe y contiene tu `GROQ_API_KEY` (o define la variable de entorno `GROQ_API_KEY`)

### La IA da respuestas imprecisas
**Solución 1:** Usa versión "Mejorada" en lugar de "Básica"
**Solución 2:** Usa versión "RAG" y añade contenido verificado
**Solución 3:** Reformula la pregunta con más contexto

### No carga la página
**Problema:** Servidor PHP no está corriendo
**Solución:** Ejecuta `php -S localhost:8000` en la carpeta del proyecto

## 📂 Estructura del Proyecto

```
biochat-eso/
├── index.html                   # Interfaz única con selector de backend
├── chat-groq.php                # Backend básico (Groq)
├── chat-groq-mejorado.php       # Backend mejorado (Groq, anti-alucinación)
├── chat-groq-rag.php            # Backend con RAG (Groq)
├── chat.php                     # Backend local (Ollama, phi3:mini)
├── request-helpers.php          # CORS, validación de método y de entrada (compartido)
├── conocimiento.json            # Base de conocimiento verificada (RAG)
├── sinonimos.json               # Sinónimos para la búsqueda RAG
├── config.php.example           # Plantilla de configuración (copiar a config.php)
├── config.php                   # Tu configuración local (NO se versiona)
├── composer.json                # Metadatos y requisitos (php>=7.4, ext-curl, ext-json)
├── GUIA_CONTENIDO_VERIFICADO.md # Guía para añadir contenido
├── LICENSE                      # Licencia MIT
└── README.md                    # Este archivo
```
## 📈 Roadmap y Plan de Mejora (Escalabilidad RAG)

El sistema RAG actual (chat-groq-rag.php) utiliza un array interno en PHP y la función strpos() para buscar coincidencias exactas. Aunque es un enfoque excelente y educativo para empezar, tiene limitaciones al escalar a todo el temario de la ESO (no entiende sinónimos, es sensible a faltas de ortografía y el rendimiento disminuye con textos muy largos).

Para hacer el proyecto más robusto y profesional, proponemos el siguiente plan de evolución:

### Fase 1: Separación de datos (Corto plazo)

- Extraer el conocimiento de la variable $baseConocimiento hacia archivos externos (por ejemplo, múltiples archivos .txt o un conocimiento.json).
- Evitar mezclar la base de datos con la lógica de PHP.

### Fase 2: Búsqueda inteligente (Medio plazo)

- Mejorar el algoritmo de búsqueda para tolerar errores ortográficos (ej. célula vs celula).
- Añadir soporte para sinónimos básicos y evitar inyectar información duplicada en el prompt.

### Fase 3: RAG Semántico y Bases de Datos Vectoriales (Largo plazo)

- Generar Embeddings del temario (representaciones matemáticas del texto).
- Integrar una Base de Datos Vectorial ligera (como ChromaDB o Qdrant) o usar extensiones de SQLite (como sqlite-vss). Esto permitirá buscar por significado conceptual en lugar de hacer coincidencia exacta de palabras, evitando que el RAG falle cuando el alumno formule la pregunta con otras palabras.

## 🤝 Contribuir

Este es un proyecto educativo de código abierto. Ideas para contribuir:

1. Añadir más contenido verificado a la base de conocimiento
2. Mejorar las técnicas de validación
3. Añadir nuevas funcionalidades (imágenes, diagramas)
4. Traducir a otros idiomas
5. Crear versiones para otras asignaturas

## 📝 Licencia

Distribuido bajo licencia **MIT**. Ver el archivo [`LICENSE`](LICENSE) para el texto completo. Úsalo, modifícalo y compártelo libremente.

**Nota importante:** Respeta los derechos de autor al copiar contenido de libros de texto. Usa solo extractos con fines educativos.

## 👨‍💻 Autores

Proyecto padre-hijo para asignatura de biología de ESO.

## 🙏 Agradecimientos

- [Anthropic](https://www.anthropic.com/) por Claude
- [Google](https://www.google.com/) por Gemini
- [Groq](https://groq.com/) por su API gratuita y rápida
- [Tailwind CSS](https://tailwindcss.com/) por el framework CSS
- Los profesores de biología que inspiran a estudiantes
- Estudiantes como Mario, con sus inquietudes y sus preguntas, aunque a veces nos pongan de los nervios 🫶

---

**¿Preguntas? ¿Sugerencias?** Abre un issue o contribuye al proyecto.

*Desarrollado con ❤️ para estudiantes de ESO*
