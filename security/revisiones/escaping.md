# Revisión de escape de salida: etiquetas sin escape en las plantillas

**Fecha:** 2026-10-18
**Rama:** security/audit-escaping
**Alcance:** todas las plantillas Mustache del plugin (`src/templates/`) y el
código PHP que arma su contexto.
**Método:** buscar las etiquetas sin escape (`{{{ }}}`) en las plantillas y
comprobar que el contenido que reciben haya pasado por `format_text()` antes de
llegar a la plantilla. Las etiquetas dobles (`{{ }}`) no se revisan porque
Mustache escapa su salida automáticamente.

## Resumen

- Plantillas Mustache revisadas: 4.
- Etiquetas sin escape (`{{{ }}}`) encontradas: 2.
- Llamadas a `format_text()` en el plugin: 0.
- Contenido que se pueda confirmar que pasó por `format_text()`: 0.
- Problema explotable encontrado: ninguno (ver Conclusión).

## Etiquetas sin escape encontradas

| Archivo | Línea | Etiqueta | Estado |
|---|---|---|---|
| src/templates/post.mustache | 53 | `{{{content}}}` | Sin confirmar: ningún PHP renderiza la plantilla ni arma su contexto. |
| src/templates/comments.mustache | 60 | `{{{content}}}` | Sin confirmar: ningún PHP renderiza la plantilla ni arma su contexto. |

`content` es el texto que publica el usuario. Al usar `{{{ }}}` (sin escape), la
plantilla exige que el valor ya venga saneado con `format_text()`; hoy ninguna
parte del plugin llama a esa función, así que no se puede confirmar el saneado.
`src/index.php` solo imprime el encabezado y el pie de Moodle y todavía no
renderiza ninguna plantilla (no hay llamadas a `render_from_template`), por lo
que ambas etiquetas están en plantillas que aún no tienen datos.

## Plantillas revisadas sin etiquetas sin escape

- src/templates/listing.mustache: usa solo etiquetas dobles (`{{ }}`), que Mustache escapa.
- src/templates/wall.mustache: no contiene etiquetas, solo un `div` vacío.

## Otros archivos revisados sin etiquetas sin escape

La búsqueda de `{{{ }}}` se hizo sobre todo `src/`. En estos archivos no
aparece ninguna etiqueta y, en los PHP, tampoco `format_text` ni
`render_from_template`:

- src/README.md
- src/amd/src/follow.js
- src/amd/src/wall.js
- src/classes/event/comment_created.php
- src/classes/event/listing_created.php
- src/classes/event/post_created.php
- src/classes/event/post_deleted.php
- src/classes/event/reaction_added.php
- src/classes/form/listing_form.php
- src/classes/form/post_form.php
- src/classes/local/hook_callbacks.php
- src/classes/local/listing_service.php
- src/classes/local/post_service.php
- src/db/access.php
- src/db/events.php
- src/db/install.xml
- src/db/messages.php
- src/db/services.php
- src/db/tag.php
- src/index.php
- src/lang/en/local_chuspasocial.php
- src/lang/es/local_chuspasocial.php
- src/pix/icon.svg
- src/settings.php
- src/styles.css
- src/tests/behat/wall.feature
- src/tests/lib_test.php
- src/tests/local/persistent/report_test.php
- src/tests/local/post_service_test.php
- src/version.php

## Conclusión

No hay hoy ninguna salida de contenido de usuario sin sanear, porque ninguna
plantilla se renderiza todavía desde PHP: las dos etiquetas sin escape están en
plantillas que aún no reciben datos. Por eso no se abre ningún issue
`security:` nuevo. Queda como requisito obligatorio que, cuando se implemente el
renderizado, el `content` de publicaciones y comentarios pase por
`format_text()` (con el `format` guardado en la publicación o el comentario)
antes de armar el contexto; recién entonces corresponde volver a revisar estas
dos líneas.
