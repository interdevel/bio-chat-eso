# 📚 Guía para añadir contenido verificado

## ¿Por qué es importante?

Los modelos de IA pueden "alucinar" (inventar información). Para evitarlo en un contexto educativo, usamos **RAG (Retrieval-Augmented Generation)**: proporcionamos al modelo información verificada de libros de texto.

## Cómo funciona

1. **Tú añades** información de tus libros de texto a `$baseConocimiento`
2. El sistema **busca** si tu pregunta coincide con algún tema
3. Si encuentra coincidencia, **inyecta** ese contenido en el prompt
4. La IA **usa solo** esa información verificada para responder

## Cómo añadir nuevo contenido

Edita `chat-groq-rag.php` y añade entradas al array `$baseConocimiento`:

```php
$baseConocimiento = [
    "nombre_tema" => "Contenido textual exacto del libro...",
    
    // Ejemplo real:
    "respiracion_celular" => "La respiración celular es el proceso mediante el cual las células obtienen energía de la glucosa. Ocurre en las mitocondrias. Tiene tres fases: glucólisis (citoplasma), ciclo de Krebs (matriz mitocondrial) y cadena respiratoria (membrana interna). La ecuación general es: C₆H₁₂O₆ + 6O₂ → 6CO₂ + 6H₂O + ATP (energía). Es el proceso opuesto a la fotosíntesis.",
];
```

## Consejos para añadir contenido

⚠️ **Copia textualmente** de tu libro de texto (respeta derechos de autor, solo para uso educativo)
✅ **Incluye datos precisos**: fórmulas, nombres científicos, fechas
✅ **Usa términos clave** como nombre del tema (ej: "respiracion_celular", "sistema_nervioso")
✅ **150-200 palabras** por tema (ni muy corto ni muy largo)
✅ **Lenguaje ESO**: claro y apropiado para tu nivel

❌ **NO inventes** información
❌ **NO copies de Wikipedia** sin verificar (puede tener errores)
❌ **NO uses fuentes no académicas** (blogs, foros)

## Fuentes verificadas recomendadas

1. Tu libro de texto de biología
2. Libros de texto oficiales de editoriales (Santillana, SM, Anaya) ⚠️ ¡Ojo! Derechos de autor y copyright
3. Apuntes de tu profesor
4. Enciclopedias científicas para estudiantes
5. Fuentes de información fiables y ABIERTAS (Sugerencias RAG)
    - Para solucionar el problema del copyright y construir un RAG 100% legal y abierto, te recomiendo usar estas fuentes (Recursos Educativos Abiertos - REA):
        - Proyecto Biosfera (INTEF): Es el portal del Ministerio de Educación de España. Tiene unidades didácticas completas de Biología y Geología para toda la ESO. El contenido es fiable, adaptado al currículo español y público.
        - Procomún (Red de Recursos Educativos Abiertos): También del Ministerio (España). Contiene miles de recursos creados por profesores, bajo licencias Creative Commons.
        - CK-12 (ck12.org): Tienen libros de texto completos de ciencias y biología (muchos en español) creados específicamente para educación secundaria y bajo licencia abierta. Son perfectos para alimentar tu base de datos.
        - OpenStax (Biología): Aunque su nivel suele ser de bachillerato/universidad, los conceptos básicos están perfectamente explicados y su uso es 100% libre y gratuito.

## Ejemplo de workflow con tu padre

```
TÚ (hijo):
1. Lees tu libro sobre "sistema digestivo"
2. Haces un resumen de 150 palabras con los datos importantes
3. Se lo pasas a tu padre

TU PADRE:
4. Lo añade al array $baseConocimiento con la clave "sistema_digestivo"
5. Prueba haciendo preguntas sobre el tema
6. Verifican que las respuestas usen esa información
```

## Temas sugeridos para empezar (curriculum ESO)

### 1º ESO
- [ ] Clasificación de seres vivos (5 reinos)
- [ ] Partes de la célula
- [ ] Funciones vitales (nutrición, relación, reproducción)
- [ ] Ecosistemas y cadenas tróficas

### 2º ESO
- [ ] Aparato digestivo
- [ ] Aparato respiratorio
- [ ] Aparato circulatorio
- [ ] Sistema excretor
- [ ] Hábitos saludables

### 3º ESO
- [ ] Organización celular
- [ ] Tejidos, órganos, sistemas
- [ ] Enfermedades infecciosas
- [ ] Sistema inmunitario
- [ ] Genética mendeliana básica

### 4º ESO
- [ ] Estructura del ADN
- [ ] Mutaciones y evolución
- [ ] Teoría de Darwin
- [ ] Selección natural
- [ ] Biotecnología
- [ ] Dinámica de ecosistemas

## Verificación de calidad

Antes de añadir contenido, pregúntate:

1. ¿Está en mi libro de texto? → ✅ Añadir
2. ¿Lo dijo mi profesor en clase? → ✅ Añadir (verificar con apuntes)
3. ¿Lo encontré en internet? → ⚠️ Verificar primero con fuentes académicas
4. ¿Me lo inventé / lo recuerdo vagamente? → ❌ NO añadir

## Ventajas de este sistema

✅ **Respuestas precisas** basadas en tu curriculum
✅ **Sin alucinaciones** cuando hay contexto disponible  
✅ **Personalizable** para tu clase/colegio específico
✅ **Educativo** para ti (aprendes resumiendo contenido)
✅ **Confiable** para tus compañeros que lo usen

## Próximos pasos avanzados (opcional)

- Convertir PDFs de libros de texto a base de conocimiento
- Crear categorías por curso (1º ESO, 2º ESO...)
- Añadir imágenes de diagramas del libro
- Sistema de votos "¿fue útil?" para mejorar respuestas
