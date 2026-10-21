# Política de Seguridad

## Reportar una vulnerabilidad

Si encuentras una vulnerabilidad de seguridad:

1. **No** crees un issue público
2. Repórtala mediante GitHub Private Vulnerability Reporting en `Security` → `Advisories` → `Report a vulnerability`
3. Incluye descripción detallada del problema
4. Espera confirmación antes de divulgar

## Qué se considera una vulnerabilidad

En ChuspaSocial se considera una vulnerabilidad cualquier fallo que permita comprometer la seguridad, privacidad o acceso autorizado a la información de los usuarios.

Algunos ejemplos son:

- Acceder o visualizar publicaciones de materias en las que el usuario no está inscrito.
- Ejecutar código malicioso mediante ataques XSS (Cross-Site Scripting).
- Realizar acciones no autorizadas mediante ataques CSRF (Cross-Site Request Forgery).
- Acceder, descargar o visualizar archivos privados sin los permisos correspondientes.

## Análisis de seguridad

Este proyecto ejecuta análisis automático de seguridad
en cada PR mediante GitHub Actions.

## Versiones soportadas

ChuspaSocial está en fase `0.x` (pre-1.0), por lo que solo se atienden reportes de seguridad sobre la última versión del plugin, ejecutándose en Moodle 4.5 LTS.

| Componente | Versión | Soportada |
|---|---|---|
| Plugin `local_chuspasocial` | 0.x (última versión) | ✅ |
| Plugin `local_chuspasocial` | Versiones 0.x anteriores a la última | ❌ |
| Moodle | 4.5 LTS | ✅ |
| Moodle | Otras versiones | ❌ |