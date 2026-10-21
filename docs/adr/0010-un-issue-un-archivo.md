# ADR-010: Un issue, un archivo

## Estado

Aceptado.

## Contexto

ChuspaSocial se desarrolla mediante trabajo paralelo de aproximadamente 100 personas. Cuando varios issues modifican los mismos archivos o dependen entre sí, aumenta la posibilidad de conflictos entre ramas y se dificulta la revisión de los cambios.

Para facilitar el trabajo paralelo, se busca que cada issue sea pequeño, independiente y pueda revisarse sin depender de otros cambios.

## Decisión

Cada issue debe modificar un solo archivo y no depender de otros issues.

Esta decisión permite que varias personas trabajen en paralelo con menor riesgo de conflictos. Además, mantiene los Pull Requests pequeños y facilita una revisión rápida de cada cambio.

## Alternativas descartadas

- Permitir que un mismo issue modifique varios archivos. Se descarta porque aumenta la posibilidad de conflictos y hace que los Pull Requests sean más grandes y difíciles de revisar.
- Hacer que un issue dependa de otros issues. Se descarta porque dificulta el trabajo paralelo y puede bloquear el avance de otros colaboradores.

## Consecuencias

Los issues serán más pequeños e independientes, permitiendo que aproximadamente 100 personas puedan trabajar en paralelo con menos conflictos.

Los Pull Requests serán más fáciles y rápidos de revisar. Cuando un cambio requiera modificar varios archivos, deberá dividirse en issues independientes siempre que sea posible.
