# 🧬 BioChat ESO - Asistente de Biología con IA

Proyecto educativo de código abierto para estudiantes de ESO. Un chatbot de biología con IA que permite probar distintos backends: Groq con RAG, Groq básico y Ollama local.

## 📋 Características

- ✅ **3 backends seleccionables en la interfaz**: Groq RAG, Groq básico y Ollama local.
- ✅ **Interfaz unificada** que permite cambiar de backend fácilmente.
- ✅ **RAG (Retrieval-Augmented Generation)** con resúmenes propios de fuentes abiertas.
- ✅ **Búsqueda RAG simple** con coincidencias por tema, sinónimos y tolerancia básica a faltas.
- ✅ **Entrada protegida**: método POST, validación del prompt y allowlist CORS opcional.
- ✅ **Interfaz amigable** diseñada para estudiantes de 12-16 años.
- ✅ **Código abierto** para compartir y mejorar.

## 🚀 Inicio Rápido

### 1. Requisitos

- PHP 7.4+ con cURL y JSON habilitados.
- Navegador web moderno.
- Cuenta gratuita en [Groq](https://console.groq.com) para usar `chat-groq.php` y `chat-groq-rag.php`.
- Opcional: Ollama con `phi3:mini` para usar `chat.php`.

### 2. Obtener API Key de Groq

1. Crea una cuenta gratuita en https://console.groq.com
2. Ve a "API Keys" y crea una nueva
3. Copia la API key

### 3. Configuración

1. Copia `config.php.example` a `config.php` en la raíz del proyecto.
2. Abre `config.php` y pega tu API key de Groq.

```php
<?php
// En tu archivo config.php (¡NO LO SUBAS A GIT! Está en .gitignore)
define('GROQ_API_KEY', 'gsk_tu_api_key_aqui');

// Allowlist de orígenes (CORS). Vacío = solo mismo origen (la propia index.html).
// Añade tu dominio aquí solo si vas a llamar a los endpoints desde otra web.
define('ALLOWED_ORIGINS', [
    // 'https://tudominio.com',
]);
```

No hay que editar las claves dentro de los archivos `chat-*.php`: todos leen la configuración desde `config.php` o desde la variable de entorno `GROQ_API_KEY`.

### 4. Ejecutar

```bash
# En la carpeta del proyecto
php -S localhost:8000

# Abre en el navegador
http://localhost:8000/index.html
```

`index.html` incluye un selector para cambiar entre los tres backends visibles: `chat-groq-rag.php`, `chat-groq.php` y `chat.php`.

## 📚 Backends Del Selector

### 🛡️ Groq RAG (`chat-groq-rag.php`) - Seleccionado por defecto

**Cuándo usar:** cuando quieras que las respuestas se apoyen en la base de conocimiento del proyecto.

**Características:**
- Usa `conocimiento.json` como base de conocimiento.
- Usa `sinonimos.json` para mejorar coincidencias.
- Normaliza algunas tildes y tolera errores ortográficos sencillos con Levenshtein.
- Inyecta como máximo 2 contextos relevantes en el prompt.
- Usa temperatura baja (`0.1`) para reducir creatividad.
- Añade una nota cuando ha usado contexto de la base de conocimiento.

**Importante:** el backend indica al modelo que use solo la base de conocimiento, pero sigue siendo una respuesta generada por IA. Para uso académico, conviene revisar la respuesta con el libro, el profesor o la fuente original.

### 🚀 Groq Básico (`chat-groq.php`)

**Cuándo usar:** pruebas rápidas y preguntas generales.

**Características:**
- Configuración mínima.
- Respuestas rápidas.
- Usa temperatura `0.7`, por lo que puede ser menos conservador.
- No usa base de conocimiento.

### 🧠 Ollama Local (`chat.php`)

**Cuándo usar:** si quieres probar el proyecto sin enviar preguntas a una API externa.

**Características:**
- Requiere Ollama ejecutándose en `http://localhost:11434`.
- Usa el modelo `phi3:mini`.
- No requiere API key de Groq.
- Depende de que el modelo esté descargado y en marcha en tu máquina.

## ⭐ Backend Adicional

El repositorio incluye también `chat-groq-mejorado.php`, una versión Groq con prompt más estricto, temperatura `0.3` y validación posterior de frases sospechosas. No aparece actualmente en el selector de `index.html`; si quieres usarlo desde la interfaz, añade una opción al `<select>` apuntando a `chat-groq-mejorado.php`.

## 🎯 Comparativa

| Característica | Groq RAG | Groq Básico | Ollama Local |
|----------------|----------|-------------|--------------|
| **Backend** | Groq API | Groq API | Ollama local |
| **Archivo** | `chat-groq-rag.php` | `chat-groq.php` | `chat.php` |
| **Modelo configurado** | `llama-3.3-70b-versatile` | `llama-3.3-70b-versatile` | `phi3:mini` |
| **Temperatura** | 0.1 | 0.7 | 0.7 |
| **Base de conocimiento** | ✅ | ❌ | ❌ |
| **API key Groq** | ✅ | ✅ | ❌ |
| **Uso recomendado** | Estudio con contenido verificado | Pruebas rápidas | Pruebas locales |

## 🔧 Técnicas Implementadas

### 1. Temperatura baja en RAG

```php
"temperature" => 0.1
```

Reduce la creatividad del modelo y favorece respuestas más pegadas al contexto proporcionado.

### 2. Prompt del sistema

Los backends usan instrucciones de profesor de biología de ESO, lenguaje claro, respuestas concisas y límites sobre invención de datos.

### 3. Base de conocimiento externa

```json
{
    "fotosintesis": "Resumen propio basado en fuentes abiertas sobre la fotosíntesis."
}
```

`chat-groq-rag.php` carga `conocimiento.json`, busca temas relevantes y añade ese contexto al prompt.

### 4. Sinónimos y tolerancia básica

`sinonimos.json` permite relacionar términos como `adn`, `cromosomas` o `acido desoxirribonucleico`. Además, el código usa Levenshtein para tolerar algunas faltas sencillas.

### 5. Validación de entrada

`request-helpers.php` centraliza:

- CORS por allowlist (`ALLOWED_ORIGINS`).
- Rechazo de métodos distintos de POST.
- Lectura y validación del campo `prompt`.
- Límite de longitud de entrada.

## 📖 Añadir Contenido Verificado

El contenido de `conocimiento.json` debe ser original del proyecto: resúmenes propios, redactados con tus palabras, a partir de fuentes abiertas o materiales que tengas derecho a reutilizar.

Ejemplo válido:

```json
{
    "respiracion_celular": "La respiración celular es el proceso por el que las células obtienen energía a partir de moléculas como la glucosa. En las células eucariotas ocurre principalmente en las mitocondrias y permite producir ATP, que la célula utiliza para realizar sus funciones."
}
```

Pasos recomendados:

1. Consulta una fuente abierta y fiable.
2. Redacta un resumen propio de 100-200 palabras.
3. Añade una clave clara en `conocimiento.json`, sin tildes ni espacios.
4. Añade sinónimos relacionados en `sinonimos.json` si ayuda a encontrar el tema.
5. Prueba varias preguntas reales de estudiantes.

Ver guía completa: `GUIA_CONTENIDO_VERIFICADO.md`.

## 🎓 Temas Sugeridos por Curso

### 1º ESO
- Clasificación seres vivos
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
- Estructura del ADN
- Evolución y selección natural
- Biotecnología
- Dinámica de ecosistemas

## ⚠️ Limitaciones y Precauciones

### Para Estudiantes
- ✅ Usa BioChat como **complemento** de estudio.
- ✅ **Verifica** información importante con tu libro, profesor o fuente original.
- ❌ **NO copies** respuestas directamente en trabajos.
- ❌ **NO sustituye** el estudio ni la comprensión.

### Para Profesores
- ✅ RAG es el modo más recomendable para uso en aula.
- ✅ Revisa respuestas antes de usarlas en evaluaciones.
- ✅ Anima a estudiantes a contrastar información.
- ⚠️ La IA puede equivocarse, incluso con contexto.

### Aspectos Técnicos
- Groq tiene límites de uso gratuito.
- Los backends Groq requieren conexión a internet.
- Ollama requiere tener el servicio local activo.
- Las respuestas RAG dependen de la calidad y cobertura de `conocimiento.json`.

### Seguridad
- La API key se carga desde `config.php` (en `.gitignore`) o variable de entorno. Nunca la subas al repositorio.
- Los endpoints aplican allowlist de orígenes (`ALLOWED_ORIGINS`), exigen método POST y validan la entrada. Ver `request-helpers.php`.
- ⚠️ Sin límite de peticiones por IP (rate limiting): si expones los endpoints públicamente, configúralo a nivel de servidor/proxy para reducir el riesgo de abuso de tu cuota Groq.

## 🛠️ Solución de Problemas

### Error: "Error de configuración: API Key no definida"
**Problema:** API key incorrecta o no configurada.  
**Solución:** revisa que `config.php` existe y contiene `GROQ_API_KEY`, o define la variable de entorno `GROQ_API_KEY`.

### Error usando Ollama
**Problema:** Ollama no está ejecutándose o no tiene el modelo.  
**Solución:** ejecuta `ollama serve` y asegúrate de tener disponible `phi3:mini`.

### La IA da respuestas imprecisas
**Solución 1:** usa `Groq RAG`.  
**Solución 2:** añade más contenido verificado a `conocimiento.json`.  
**Solución 3:** añade sinónimos a `sinonimos.json`.  
**Solución 4:** reformula la pregunta con más contexto.

### No carga la página
**Problema:** servidor PHP no está corriendo.  
**Solución:** ejecuta `php -S localhost:8000` en la carpeta del proyecto.

## 📂 Estructura Del Proyecto

```text
bio-chat-eso/
├── index.html                   # Interfaz única con selector de backend
├── chat-groq.php                # Backend básico (Groq)
├── chat-groq-mejorado.php       # Backend mejorado (Groq, no incluido en el selector)
├── chat-groq-rag.php            # Backend con RAG (Groq)
├── chat.php                     # Backend local (Ollama, phi3:mini)
├── request-helpers.php          # CORS, método POST y validación de entrada
├── conocimiento.json            # Resúmenes propios de fuentes abiertas para RAG
├── sinonimos.json               # Sinónimos para la búsqueda RAG
├── config.php.example           # Plantilla de configuración
├── config.php                   # Configuración local (NO se versiona)
├── composer.json                # Metadatos y requisitos
├── GUIA_CONTENIDO_VERIFICADO.md # Guía para añadir contenido
├── LICENSE                      # Licencia MIT
└── README.md                    # Este archivo
```

## 📈 Roadmap

El RAG actual es intencionadamente sencillo: carga un JSON, busca coincidencias por tema/sinónimos y añade contexto al prompt. Es fácil de entender y modificar, pero no sustituye a un sistema semántico completo.

Ideas de mejora:

1. Añadir tests automáticos para validación de entrada y búsqueda RAG.
2. Añadir metadatos por tema: curso, fuente abierta, licencia y fecha de revisión.
3. Separar la base de conocimiento en varios archivos por curso o unidad.
4. Mejorar la búsqueda con embeddings y una base vectorial ligera.
5. Añadir rate limiting si se despliega públicamente.

## 🤝 Contribuir

Este es un proyecto educativo de código abierto. Ideas para contribuir:

1. Añadir más resúmenes propios basados en fuentes abiertas.
2. Mejorar las técnicas de validación.
3. Añadir nuevas funcionalidades (imágenes, diagramas).
4. Traducir a otros idiomas.
5. Crear versiones para otras asignaturas.

## 📝 Licencia

Distribuido bajo licencia **MIT**. Ver el archivo [`LICENSE`](LICENSE) para el texto completo.

El código del proyecto es MIT. El contenido de `conocimiento.json` está formado por resúmenes propios redactados a partir de fuentes abiertas; si añades nuevo contenido, usa textos originales o fuentes con licencia compatible.

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
