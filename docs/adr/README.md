# ARCHITECTURE DECISION RECORDS (ADR)

Los Architecture Decision Records (ADR) son documentos que registran decisiones importantes de arquitectura tomadas durante el desarrollo del proyecto.

Su objetivo es conservar el contexto de las decisiones para que el equipo pueda entender qué se decidió, por qué se decidió y cuáles fueron sus consecuencias.

## Estructura de un ADR

Cada ADR debe contener las siguientes cuatro secciones:

### 1. Estado

Indica la situación actual de la decisión.

Ejemplos:

- Propuesto
- Aceptado
- Rechazado
- Reemplazado
- Obsoleto

### 2. Contexto

Describe el problema, necesidad o situación que llevó al equipo a tomar una decisión.

Aquí se debe explicar la información relevante que se tuvo en cuenta antes de decidir.

### 3. Decisión

Describe la solución o alternativa que el equipo decidió adoptar.

Debe indicar claramente qué se decidió implementar y, cuando sea necesario, las razones principales de la decisión.

### 4. Consecuencias

Describe los efectos de la decisión.

Puede incluir beneficios, limitaciones, riesgos, costos técnicos o cambios que deberán considerarse en el futuro.

## Ejemplo

```text
# ADR-001: Uso de PostgreSQL

## Estado

Aceptado

## Contexto

El proyecto necesita una base de datos relacional para almacenar y consultar la información de los usuarios.

## Decisión

Se utilizará PostgreSQL como sistema gestor de base de datos.

## Consecuencias

El equipo deberá configurar PostgreSQL en los entornos de desarrollo y producción.