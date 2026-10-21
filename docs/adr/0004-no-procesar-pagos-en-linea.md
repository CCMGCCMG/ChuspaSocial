# ADR-004: No procesar pagos en línea

## Estado

Aceptado.

## Contexto

El Marketplace de ChuspaSocial tiene como objetivo permitir que los usuarios publiquen anuncios de libros y contacten con vendedores. El proyecto no necesita actuar como plataforma de pago ni gestionar transacciones financieras para cumplir ese objetivo.

Incorporar pagos en línea aumentaría de forma importante la complejidad técnica y las responsabilidades relacionadas con datos financieros, seguridad, proveedores de pago, devoluciones y disputas.

También introduciría riesgos legales que no forman parte del alcance del proyecto, como definir responsabilidades frente a cobros fallidos, reembolsos y reclamos, cumplir las condiciones del proveedor de pagos y atender obligaciones aplicables al tratamiento de información financiera y a la protección del consumidor. Estos requisitos pueden variar según el proveedor y la jurisdicción, por lo que evitarlos reduce el alcance legal que tendría que asumir ChuspaSocial.

## Decisión

El Marketplace de ChuspaSocial solo publicará anuncios y facilitará el contacto entre usuarios. ChuspaSocial no procesará, almacenará ni confirmará pagos en línea.

Cualquier acuerdo de compra y pago se realizará fuera de ChuspaSocial entre comprador y vendedor.

## Alternativas descartadas

- Integrar una pasarela de pagos dentro de ChuspaSocial. Se descarta porque requeriría integrar y mantener servicios externos, gestionar estados de transacción y aumentar la superficie de seguridad del sistema.
- Almacenar información de pago para completar compras desde el Marketplace. Se descarta por los riesgos técnicos y de protección de datos asociados al manejo de información financiera.

## Consecuencias

El Marketplace mantiene un alcance más simple: publicar anuncios y facilitar el contacto entre usuarios.

Se evitan responsabilidades técnicas relacionadas con cobros, reembolsos, conciliación de transacciones y almacenamiento de información financiera. Como limitación, ChuspaSocial no podrá verificar si una operación económica se completó correctamente, ya que el pago ocurre fuera de la plataforma.
