# Modelo de amenazas

Análisis de qué puede salir mal en el plugin `local_chuspasocial` (Moodle 4.5 LTS)
y cómo se mitiga. Las mitigaciones usan las APIs de seguridad de Moodle y son las
que debe cumplir el código del plugin.

## Activos

| ID | Activo | Por qué importa |
|---|---|---|
| A-01 | Cuentas y sesiones de usuarios | Permiten actuar en nombre de estudiantes, docentes y administradores. |
| A-02 | Publicaciones, comentarios y anuncios | Contenido visible para otros usuarios; puede llevar código malicioso o información falsa. |
| A-03 | Contenido de las materias (muro, tareas, anuncios oficiales) | Solo debe verlo quien está matriculado en la materia. |
| A-04 | Datos personales (nombre, foto, seguidores, seguidos) | Información privada de los usuarios. |
| A-05 | Anuncios del marketplace | Ofertas entre usuarios; pueden usarse para estafas. |

## Actores

| ID | Actor | Descripción |
|---|---|---|
| AC-01 | Estudiante malicioso | Usuario autenticado que intenta ver o modificar lo que no le corresponde. |
| AC-02 | Atacante externo | Sin cuenta; intenta que un usuario autenticado ejecute acciones sin saberlo. |
| AC-03 | Spammer o bot | Cuenta que publica contenido masivo o repetitivo. |
| AC-04 | Estafador en el marketplace | Usuario que publica ofertas falsas o intenta cobrar por fuera. |

## Amenazas y mitigaciones

| ID | Amenaza | Actor | Activo | Mitigación |
|---|---|---|---|---|
| T-01 | XSS: una publicación, comentario, anuncio o parámetro de la URL lleva HTML o JavaScript que se ejecuta en el navegador de otro usuario. | AC-01, AC-02, AC-03 | A-01, A-02 | Validar la entrada con required_param / optional_param y su tipo PARAM, y escapar toda salida con format_text, format_string o s(); en Mustache usar solo {{ }} (nunca {{{ }}} con datos del usuario). |
| T-02 | CSRF: un sitio externo hace que el usuario publique, borre, siga o publique en el marketplace sin querer. | AC-02 | A-01, A-02, A-05 | Toda acción que cambia datos exige require_sesskey() en formularios y peticiones; los servicios web AJAX se declaran en db/services.php con ajax => true, y Moodle comprueba la sesión al recibir la llamada. |
| T-03 | Acceso a materias ajenas: un usuario ve el muro, las tareas o los anuncios de una materia en la que no está matriculado cambiando el id en la URL. | AC-01 | A-03, A-04 | require_login(course) y require_capability sobre el contexto de la materia en cada página y servicio; filtrar las consultas por el contexto y la matrícula del usuario, nunca solo por el id recibido. |
| T-04 | Spam: publicaciones o comentarios masivos que saturan el muro. | AC-03 | A-02 | RF-042 limita en el servidor la longitud máxima de una publicación y la cantidad máxima de imágenes por publicación: acota el tamaño de cada mensaje, pero no cuántos se publican. Propuesta de diseño (todavía sin requerimiento): límite de publicaciones por usuario en un intervalo de tiempo y opción de reportar y ocultar contenido. |
| T-05 | Fraude en el marketplace: ofertas falsas, cobro adelantado o suplantación del vendedor. | AC-04 | A-05 | Propuesta de diseño (el marketplace todavía no tiene requerimientos): anuncios asociados siempre a la cuenta real del autor, botón para reportar anuncios, moderación que puede ocultar anuncios y bloquear cuentas, y aviso visible de que el plugin no gestiona pagos. |
