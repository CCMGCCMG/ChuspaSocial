# Casos de prueba funcionales — Tags

Casos manuales para las funciones de tags (etiquetas). Cada caso referencia el
requerimiento funcional (RF) de `docs/requerimientos.md` que verifica.

| ID | RF | Precondición | Pasos | Resultado esperado |
|---|---|---|---|---|
| CP-TAG-01 | RF-015 | Usuario autenticado que puede crear publicaciones en el muro. | 1. Abrir el muro. 2. Crear una publicación nueva. 3. Escribir una etiqueta en el campo de etiquetas. 4. Guardar la publicación. | La etiqueta queda asociada a la publicación al guardarla. |
| CP-TAG-02 | RF-015 | Usuario autenticado que puede crear publicaciones; en Moodle existen tags predefinidos. | 1. Abrir el muro. 2. Crear una publicación nueva. 3. Elegir un tag predefinido de Moodle en el campo de etiquetas. 4. Guardar la publicación. | El tag predefinido queda asociado a la publicación al guardarla. |
| CP-TAG-03 | RF-015 | Usuario autenticado con una publicación propia sin etiquetas en el muro. | 1. Abrir el muro. 2. Editar la publicación propia. 3. Escribir una etiqueta en el campo de etiquetas. 4. Guardar los cambios. | La etiqueta queda asociada a la publicación editada al guardarla. |
| CP-TAG-04 | RF-015 | En el muro hay una publicación con etiquetas asociadas, creada por otro usuario. | 1. Iniciar sesión con un usuario distinto del autor. 2. Abrir el muro. 3. Revisar la publicación. | Las etiquetas de la publicación se ven en el muro. |
| CP-TAG-05 | RF-016 | En el muro hay publicaciones con una misma etiqueta y otras sin ella. | 1. Abrir el muro. 2. Hacer clic sobre esa etiqueta en una publicación. | El listado se recarga y muestra únicamente las publicaciones que contienen esa etiqueta. |
| CP-TAG-06 | RF-016 | El muro está filtrado por una etiqueta específica. | 1. Elegir la opción de limpiar el filtro o restablecer el muro. 2. Revisar el listado. | El muro muestra nuevamente todas las publicaciones disponibles, sin filtro. |
