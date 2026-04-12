# 🧬 BioChat ESO - Asistente de Biología con IA

Proyecto educativo de código abierto desarrollado para estudiantes de ESO. Chatbot de biología con tres niveles de precisión según necesidades.

## 📋 Características

- ✅ **3 versiones** con diferentes niveles de precisión
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

Edita los archivos PHP y reemplaza `TU_API_KEY_AQUI` con tu API key:

```php
$GROQ_API_KEY = "gsk_tu_api_key_aqui";
```

Archivos a editar según la versión que uses:
- `chat-groq.php` (versión básica)
- `chat-groq-mejorado.php` (versión mejorada)
- `chat-groq-rag.php` (versión RAG)

### 4. Ejecutar

```bash
# En la carpeta del proyecto
php -S localhost:8000

# Abre en el navegador
http://localhost:8000/index-selector.html
```

## 📚 Versiones Disponibles

### 🚀 Versión Básica (`chat-groq.php`)

**Cuándo usar:** Pruebas rápidas, preguntas generales

**Características:**
- Configuración mínima
- Respuestas rápidas
- ⚠️ Puede tener imprecisiones ocasionales

**Archivo:** `index-basico.html`

---

### ⭐ Versión Mejorada (`chat-groq-mejorado.php`) - **RECOMENDADA**

**Cuándo usar:** Uso educativo general

**Características:**
- ✅ Temperatura baja (0.3) para mayor precisión
- ✅ System prompt con contexto curricular ESO
- ✅ Validación post-procesamiento
- ✅ Detección de posibles alucinaciones
- ✅ Instrucciones anti-invención de datos

**Archivo:** `index-mejorado.html`

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

**Archivo:** `index-rag.html`

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
3. **Añádelo** al array `$baseConocimiento` en `chat-groq-rag.php`:

```php
$baseConocimiento = [
    // ... contenido existente ...
    
    "tu_tema" => "Tu resumen verificado del libro de texto aquí..."
];
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

## 🛠️ Solución de Problemas

### Error: "timeout after 60 seconds"
**Problema:** Ollama tarda mucho en cargar el modelo
**Solución:** Usar Groq API (archivos chat-groq-*.php)

### Error: "Error de API. Verifica tu API key"
**Problema:** API key incorrecta o no configurada
**Solución:** Edita el archivo PHP y añade tu API key de Groq

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
├── index-selector.html          # Selector de versión
├── index-basico.html            # Chat básico
├── index-mejorado.html          # Chat mejorado (RECOMENDADO)
├── index-rag.html               # Chat con RAG
├── chat-groq.php                # Backend básico
├── chat-groq-mejorado.php       # Backend mejorado
├── chat-groq-rag.php            # Backend con RAG
├── GUIA_CONTENIDO_VERIFICADO.md # Guía para añadir contenido
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

Proyecto educativo de código abierto. Úsalo, modifícalo y compártelo libremente para fines educativos.

**Nota importante:** Respeta los derechos de autor al copiar contenido de libros de texto. Usa solo extractos con fines educativos.

## 👨‍💻 Autores

Proyecto padre-hijo para asignatura de biología de ESO.

## 🙏 Agradecimientos

- [Anthropic](https://www.anthropic.com/) por Claude
- [Groq](https://groq.com/) por su API gratuita y rápida
- [Tailwind CSS](https://tailwindcss.com/) por el framework CSS
- Todos los profesores de biología que inspiran a estudiantes

---

**¿Preguntas? ¿Sugerencias?** Abre un issue o contribuye al proyecto.

💡 *Desarrollado con ❤️ para estudiantes de ESO*
