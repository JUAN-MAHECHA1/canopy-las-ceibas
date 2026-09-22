<?php

return [

    // Valor que genera cada persona registrada
    'valor_por_persona' => env('CANOPY_VALOR_POR_PERSONA', 10000),

    // Horario permitido para registrar clientes SOLO fines de semana y festivos
    'horario_restringido' => [
        'inicio' => '09:00',
        'fin'    => '18:00',
    ],

    // Festivos de Colombia. Actualizar cada año (o migrar a un paquete tipo
    // spatie/laravel-holidays / API de Nager.Date si el sitio lo requiere).
    'festivos' => [
        '2026-01-01', '2026-01-12', '2026-03-23', '2026-04-02', '2026-04-03',
        '2026-05-01', '2026-05-18', '2026-06-08', '2026-06-15', '2026-06-29',
        '2026-07-20', '2026-08-07', '2026-08-17', '2026-10-12', '2026-11-02',
        '2026-11-16', '2026-12-08', '2026-12-25',
    ],

];
