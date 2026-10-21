# Estrategia de Pruebas

## Enfoque general

## Niveles de prueba

### Pruebas unitarias
- Herramienta: PHPUnit.
- Cobertura mínima: 70 % del código ubicado en `classes/`, según el requisito RNF-012.
- Ubicación de las pruebas: `src/tests/`.
- Responsable: El equipo de desarrollo realiza las pruebas unitarias y el responsable de revisión de código verifica sus resultados

### Pruebas de integración
- Herramienta:
- Alcance:
- Responsable:


### Pruebas manuales
- Frecuencia: Antes de cada release del sistema.
- Responsable: Equipo encargado de las pruebas manuales.
- Casos de prueba: Se utilizarán los casos funcionales disponibles en `tests/casos/funcionales/` para verificar el correcto funcionamiento del sistema.
- Evidencias: Los resultados y las evidencias de las pruebas realizadas se almacenarán en `tests/manuales/evidencias/`.
  

## Criterios de priorización de bugs

| Severidad | Descripción | Tiempo de resolución |
|---|---|---|
| Crítico | El sistema no funciona | Inmediato |
| Alto | Funcionalidad principal afectada | 24 horas |
| Medio | Funcionalidad secundaria afectada | 1 semana |
| Bajo | Problema cosmético | Siguiente sprint |