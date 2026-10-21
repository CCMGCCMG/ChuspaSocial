-- =========================================================
-- Consulta para medir métricas de éxito: usuarios más activos
-- Se utiliza estrictamente el 'userid' para no exponer datos personales.
-- =========================================================

SELECT 
    userid,
    SUM(es_publicacion) AS total_publicaciones,
    SUM(es_comentario) AS total_comentarios,
    (SUM(es_publicacion) + SUM(es_comentario)) AS interacciones_totales
FROM (
    SELECT userid, 1 AS es_publicacion, 0 AS es_comentario 
    FROM tabla_publicaciones
    
    UNION ALL
    
    SELECT userid, 0 AS es_publicacion, 1 AS es_comentario 
    FROM tabla_comentarios
) AS actividad
GROUP BY 
    userid
ORDER BY 
    interacciones_totales DESC;

/*
=========================================
EJEMPLO DE RESULTADO (Criterio de aceptación)
=========================================
userid  | total_publicaciones | total_comentarios | interacciones_totales
-------------------------------------------------------------------------
14      | 12                  | 34                | 46
5       | 8                   | 15                | 23
89      | 1                   | 5                 | 6
=========================================
*/