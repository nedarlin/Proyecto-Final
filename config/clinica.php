<?php

return [

    'servicios' => [
        ['nombre' => 'Evaluación general', 'descripcion' => 'Examen clínico y diagnóstico inicial, con apoyo de tomografía 3D cuando se necesita.'],
        ['nombre' => 'Ortodoncia', 'descripcion' => 'Corrección de la posición dental con brackets metálicos, estéticos o alineadores.'],
        ['nombre' => 'Implantología oral', 'descripcion' => 'Colocación de implantes dentales con indicaciones y cuidados postoperatorios.'],
        ['nombre' => 'Cirugía dental', 'descripcion' => 'Extracción de piezas retenidas y otros procedimientos quirúrgicos.'],
    ],

    'odontologos' => [
        ['id' => 1, 'nombre' => 'Dra. Torres', 'especialidad' => 'Ortodoncia'],
        ['id' => 2, 'nombre' => 'Dr. Ramírez', 'especialidad' => 'Evaluación general'],
        ['id' => 3, 'nombre' => 'Dra. Salas', 'especialidad' => 'Implantología oral'],
        ['id' => 4, 'nombre' => 'Dr. Vargas', 'especialidad' => 'Cirugía dental'],
    ],

    'horarios' => ['09:00', '10:00', '11:00', '12:00', '15:00', '16:00'],
];