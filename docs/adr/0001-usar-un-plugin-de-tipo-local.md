# ADR-001: Usar un plugin de tipo local

## Estado

Aceptado.

## Contexto

ChuspaSocial necesita ofrecer páginas propias y una navegación disponible de forma global dentro de Moodle. Estas funciones no corresponden a una actividad concreta de un curso ni a un bloque que deba colocarse en una región específica de una página.

El tipo de plugin elegido debe permitir integrar estas páginas y funcionalidades sociales sin obligar a que cada curso agregue una actividad o un bloque para acceder a ellas.

## Decisión

Implementar ChuspaSocial como un plugin de tipo local con el componente `local_chuspasocial`.

Los plugins locales permiten incorporar funcionalidad específica del sitio y páginas propias, por lo que se ajustan al alcance de ChuspaSocial y a la necesidad de disponer de navegación global.

## Alternativas descartadas

- Usar un plugin de tipo `block_`. Se descarta porque un bloque está orientado a mostrarse dentro de regiones de bloques y no representa adecuadamente las páginas y navegación global que necesita ChuspaSocial.
- Usar un plugin de tipo `mod_`. Se descarta porque los módulos de actividad se agregan dentro de cursos como actividades, mientras que ChuspaSocial necesita funcionalidades disponibles más allá de una actividad concreta.

## Consecuencias

ChuspaSocial se identificará mediante el componente `local_chuspasocial` y su código seguirá la estructura esperada para un plugin local de Moodle.

La solución podrá ofrecer páginas y navegación propias sin depender de que un docente agregue una actividad o un bloque en cada curso. A cambio, las funcionalidades específicas de ChuspaSocial deberán mantenerse dentro del alcance y convenciones de un plugin local.
