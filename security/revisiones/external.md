# Revisión de funciones externas: validate_context y capacidades en classes/external/

**Fecha:** 2026-10-21
**Rama:** security/audit-external
**Alcance:** todas las clases de `src/classes/external/`.
**Método:** en cada clase se comprobó que la función externa valide sus parámetros
con `validate_parameters()`, llame a `self::validate_context()` con el contexto
correcto antes de leer o escribir datos, y exija una capacidad con
`require_capability()` en ese mismo contexto. También se revisó que la capacidad
exigida esté definida en `src/db/access.php` y que el servicio que se invoca
exista. Las búsquedas se hicieron sobre `src/classes/external/`:

    grep -rn "validate_parameters" src/classes/external/  →  4 coincidencias
    grep -rn "validate_context" src/classes/external/  →  4 coincidencias
    grep -rn -E "require_capability|has_capability" src/classes/external/  →  5 coincidencias

`self::validate_context()` llama a `require_login($course, false, $cm, false, true)`
(external_api.php de Moodle, línea 521). En un contexto de curso eso exige que el
usuario tenga acceso al curso; en el contexto de sistema exige sesión iniciada.

## Resumen

- Archivos revisados: 5.
- Funciones externas (`extends external_api`): 4.
- Exporters (`extends exporter`): 1.
- Funciones con `validate_parameters()`, `validate_context()` y `require_capability()`: 4 de 4.
- Problemas de seguridad encontrados: 3 (se reportan como issues `security:` nuevos, uno por archivo).
- Problemas de funcionamiento encontrados: 3 (no son de seguridad; ver la última sección).

## Funciones externas revisadas

| Archivo | Línea | Verificación | Estado |
|---|---|---|---|
| src/classes/external/get_listings.php | 78 | `validate_parameters()` | OK: valida `search` (PARAM_TEXT), `page` y `perpage` (PARAM_INT). |
| src/classes/external/get_listings.php | 84-86 | `validate_context()` + `require_capability()` | OK: contexto de sistema y `local/chuspasocial:view` (definida en access.php). |
| src/classes/external/get_listings.php | 88-93 | Límites de paginación | OK: rechaza `page < 0` y `perpage` fuera de 1..50 con `invalid_parameter_exception`. |
| src/classes/external/add_comment.php | 66 | `validate_parameters()` | OK: valida `postid` (PARAM_INT) y `content` (PARAM_RAW_TRIMMED). |
| src/classes/external/add_comment.php | 71-85 | `validate_context()` + `require_capability()` | OK: toma el contexto del curso de la publicación (o el de sistema si es del muro general) y exige `local/chuspasocial:comment` (definida en access.php). |
| src/classes/external/add_comment.php | 71-76 | Visibilidad de la publicación | **Problema 1:** lee la publicación sin comprobar el campo `hidden`; se puede comentar una publicación ocultada por un moderador si se conoce su id. |
| src/classes/external/toggle_reaction.php | 64 | `validate_parameters()` | OK: valida `postid` (PARAM_INT). |
| src/classes/external/toggle_reaction.php | 68-82 | `validate_context()` + `require_capability()` | OK: mismo contexto que `add_comment` y exige `local/chuspasocial:react`. |
| src/classes/external/toggle_reaction.php | 68-73 | Visibilidad de la publicación | **Problema 2:** igual que en `add_comment`, no comprueba `hidden`; se puede reaccionar a una publicación oculta. |
| src/classes/external/report_item.php | 65 | `validate_parameters()` | OK: valida `itemtype` (PARAM_ALPHA), `itemid` (PARAM_INT) y `reason` (PARAM_RAW_TRIMMED). |
| src/classes/external/report_item.php | 71-73 | `validate_context()` + `require_capability()` | OK: contexto de sistema y `local/chuspasocial:report` (definida en access.php). |
| src/classes/external/report_item.php | 75-79 | Validación del elemento reportado | **Problema 3:** no comprueba que `itemtype` sea `post`, `comment` o `listing`, ni que el elemento exista y sea visible para quien reporta; se pueden crear reportes de elementos inexistentes o de cursos a los que el usuario no tiene acceso. |

## Archivos revisados que no son funciones externas

| Archivo | Tipo | Estado |
|---|---|---|
| src/classes/external/post_exporter.php | Exporter (`extends exporter`) | No aplica: no es un punto de entrada; no recibe parámetros de la petición. El contenido pasa por `format_text` con el contexto recibido, y `has_capability('local/chuspasocial:moderate')` (línea 158) solo se usa si la capacidad existe (`get_capability_info`). |

## Problemas de seguridad encontrados

| N.º | Archivo | Línea | Problema | Issue |
|---|---|---|---|---|
| 1 | src/classes/external/add_comment.php | 71-76 | No se comprueba que la publicación no esté oculta (`hidden = 0`) antes de comentar. | #918 |
| 2 | src/classes/external/toggle_reaction.php | 68-73 | No se comprueba que la publicación no esté oculta (`hidden = 0`) antes de reaccionar. | #919 |
| 3 | src/classes/external/report_item.php | 75-79 | No se valida `itemtype` contra los valores permitidos ni que el elemento exista y sea visible para quien reporta. | #920 |

## Problemas de funcionamiento (no de seguridad)

Estos problemas no abren ningún acceso indebido: en todos los casos la función
falla antes de modificar datos. Se dejan anotados porque impiden usarlas.

| Archivo | Línea | Problema |
|---|---|---|
| src/classes/external/toggle_reaction.php | 82 | Exige `local/chuspasocial:react`, que no está definida en `src/db/access.php`; `require_capability()` la deniega siempre. |
| src/classes/external/toggle_reaction.php | 84 | Llama a `reaction_service::toggle()`, pero `src/classes/local/reaction_service.php` no existe. |
| src/classes/external/report_item.php | 75 | Llama a `report_service::create_report()`, pero `src/classes/local/report_service.php` no existe. |
