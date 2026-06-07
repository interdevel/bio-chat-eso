# 📚 Guía para añadir contenido verificado

## ¿Por qué es importante?

Los modelos de IA pueden "alucinar" (inventar información). Para reducir ese riesgo en un contexto educativo, usamos **RAG (Retrieval-Augmented Generation)**: proporcionamos al modelo información revisada antes de responder.

## Cómo funciona

1. **Tú añades** resúmenes propios de fuentes abiertas al archivo `conocimiento.json`
2. El sistema **busca** si tu pregunta coincide con algún tema
3. Si encuentra coincidencia, **inyecta** ese contenido en el prompt
4. La IA recibe instrucciones para responder usando esa información verificada

## Cómo añadir nuevo contenido

Edita el archivo `conocimiento.json` y añade nuevas entradas al objeto JSON. Usa textos originales, redactados con tus palabras, a partir de fuentes abiertas o materiales que tengas derecho a reutilizar:

```json
{
    "respiracion_celular": "La respiración celular es el proceso mediante el cual las células obtienen energía a partir de moléculas como la glucosa. En las células eucariotas ocurre principalmente en las mitocondrias y permite producir ATP, que la célula usa para realizar sus funciones."
}
```

## Consejos para añadir contenido

✅ **Redacta resúmenes propios** a partir de fuentes abiertas o con licencia compatible
✅ **Incluye datos precisos**: fórmulas, nombres científicos, fechas
✅ **Usa términos clave** como nombre del tema (ej: "respiracion_celular", "sistema_nervioso")
✅ **100-200 palabras** por tema (ni muy corto ni muy largo)
✅ **Lenguaje ESO**: claro y apropiado para tu nivel

❌ **NO inventes** información
❌ **NO copies texto literal de libros, webs o apuntes si no tienes permiso**
❌ **NO copies de Wikipedia** sin verificar y revisar su licencia
❌ **NO uses fuentes no académicas** (blogs, foros)

## Fuentes verificadas recomendadas

1. Proyecto Biosfera / INTEF
2. Procomún (Recursos Educativos Abiertos)
3. CK-12
4. OpenStax
5. Apuntes propios o del profesor, si tienes permiso para reutilizarlos
6. Tu libro de texto como fuente de consulta, pero no como texto para copiar literalmente

## Ejemplo de workflow con tu padre

```text
TÚ (hijo):
1. Consultas una fuente abierta sobre "sistema digestivo"
2. Haces un resumen propio de 150-250 palabras con los datos importantes
3. Se lo pasas a tu padre

TU PADRE:
4. Lo añade al archivo conocimiento.json con la clave "sistema_digestivo"
5. Añade sinónimos en sinonimos.json si hace falta
6. Prueba haciendo preguntas sobre el tema
7. Verifican que las respuestas usen esa información
```

## Temas sugeridos para empezar (curriculum ESO)

### 1º ESO
- [ ] Clasificación de seres vivos
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

1. ¿Lo he redactado con mis palabras? → ✅ Añadir
2. ¿La fuente es abierta o tengo permiso para usarla? → ✅ Añadir
3. ¿Lo encontré en internet? → ⚠️ Verificar primero con fuentes académicas y revisar licencia
4. ¿Me lo inventé / lo recuerdo vagamente? → ❌ NO añadir

## Ventajas de este sistema

✅ **Respuestas más precisas** basadas en contenido revisado
✅ **Menos riesgo de alucinaciones** cuando hay contexto disponible
✅ **Personalizable** para tu clase/colegio específico
✅ **Educativo** para ti (aprendes resumiendo contenido)
✅ **Confiable** para tus compañeros que lo usen

## Próximos pasos avanzados (opcional)

- Añadir metadatos por tema: curso, fuente, licencia y fecha de revisión
- Separar el contenido por cursos o unidades
- Añadir imágenes de diagramas con licencia compatible
- Crear un sistema de votos "¿fue útil?" para mejorar respuestas
- Evolucionar a búsqueda semántica con embeddings
