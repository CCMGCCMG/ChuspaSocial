# ADR-002: Cómo mostrar el muro al ingresar a Moodle

## Estado

Aceptado.

## Contexto

El proyecto necesita definir cómo encontrar el muro al ingresar a Moodle. La decisión debe hacer que el muro sea fácil de localizar sin quitar al usuario el acceso al resto de las funciones de Moodle ni cambiar innecesariamente la experiencia de navegación.

Se comparan tres alternativas:

1. Mostrar el muro mediante un bloque en el Área personal.
2. Configurar el muro como página de inicio predeterminada después de iniciar sesión.
3. Añadir un enlace al muro en la navegación principal.

Para compararlas se consideran la visibilidad al ingresar, la cantidad de pasos para llegar al muro, el impacto sobre la navegación existente y la flexibilidad para mantener otras herramientas a disposición del usuario.

## Decisión

Se recomienda mostrar el muro mediante un bloque en el Área personal.

Esta opción permite destacar el muro dentro de un espacio personal de Moodle sin reemplazar toda la página inicial ni añadir un elemento permanente a la navegación global. El bloque puede presentar un acceso directo y, si el diseño del proyecto lo permite, una vista resumida del contenido reciente.

La implementación debe procurar que el bloque sea fácil de identificar y que el acceso al muro completo sea claro. Antes de dar por terminada la implementación, se debe comprobar que el bloque sea visible para los usuarios previstos y que sus permisos respeten las reglas de acceso al muro.

## Alternativas descartadas

1. Página de inicio por defecto

Ventajas: el muro sería lo primero que ve el usuario después de iniciar sesión y podría reducir los pasos para acceder a él.

Motivos para descartarla: reemplazar la página inicial puede desplazar información útil del Área personal y hacer que el muro tenga prioridad sobre otras funciones, incluso para usuarios que no lo necesitan como primera pantalla. También puede resultar más invasivo si Moodle se utiliza para varias actividades.

2. Enlace en la navegación principal

Ventajas: el acceso al muro estaría disponible desde distintas páginas y sería fácil de localizar de forma consistente.

Motivos para descartarla como opción principal: el enlace facilita encontrar el muro, pero no muestra contenido del muro al ingresar y normalmente exige que el usuario haga un clic adicional. Puede ser un complemento útil si las pruebas muestran que el bloque no es suficientemente visible, pero no es la opción principal recomendada.

## Consecuencias

- El Área personal tendrá un punto de acceso visible al muro sin sustituir la página completa.
- Se conserva la navegación habitual de Moodle y las demás herramientas pueden seguir disponibles.
- La visibilidad dependerá de la configuración, la posición del bloque y los permisos; estos aspectos deben verificarse durante las pruebas.
- Si los usuarios no encuentran el bloque con facilidad, se deberá revisar su ubicación y visibilidad antes de cambiar la decisión.
- La solución requiere configurar o desarrollar el bloque y comprobar su comportamiento con los perfiles de usuario relevantes.