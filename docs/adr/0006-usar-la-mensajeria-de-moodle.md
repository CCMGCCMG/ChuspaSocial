# ADR-006: Usar la mensajería de Moodle

## Estado

Aceptado.

## Contexto

ChuspaSocial necesita un mecanismo para enviar notificaciones a los usuarios y facilitar el contacto entre las personas interesadas en los libros publicados en el marketplace y sus vendedores.

Como el proyecto está desarrollado sobre Moodle, es conveniente aprovechar los servicios que la plataforma ya ofrece. Implementar un sistema de mensajería propio supondría desarrollar y mantener funcionalidades que Moodle ya proporciona, además de gestionar aspectos como los destinatarios y la integración con los usuarios de la plataforma.

## Decisión

Se decide utilizar la API de mensajería de Moodle, mediante el componente `core_message`, para gestionar el envío de mensajes y las notificaciones correspondientes dentro de ChuspaSocial.

El contacto entre compradores interesados y vendedores se apoyará en la mensajería de Moodle, utilizando los usuarios registrados en la plataforma. Las notificaciones relacionadas con las funcionalidades del plugin también podrán utilizar este mecanismo cuando corresponda.

La implementación deberá respetar las capacidades de mensajería, los permisos y las restricciones de acceso definidos por Moodle.

## Alternativas descartadas

- **Implementar un sistema de mensajería propio:** se descarta porque duplicaría funcionalidades existentes en Moodle y aumentaría el esfuerzo de desarrollo y mantenimiento.
- **Utilizar un servicio externo de mensajería:** se descarta porque añadiría una dependencia externa, requeriría configuración adicional y podría complicar la integración con las cuentas de usuario de Moodle.

## Consecuencias

- Se aprovecha la infraestructura de mensajería existente en Moodle.
- Se facilita el contacto entre los usuarios interesados en los libros y sus vendedores.
- Se reduce la necesidad de mantener un sistema de mensajería independiente.
- La solución depende de las funcionalidades y de la configuración de mensajería disponibles en Moodle.
- El plugin deberá respetar los permisos de los usuarios y utilizar correctamente la API `core_message` para los mensajes y las notificaciones.
