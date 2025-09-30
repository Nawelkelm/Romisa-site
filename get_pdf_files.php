<?php
header('Content-Type: application/json');

function getPDFsFromDirectory($dir, $baseUrl) {
    $pdfFiles = [];
    
    if (is_dir($dir)) {
        $files = glob($dir . '/*.pdf');
        
        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $relativePath = $baseUrl . basename($file);
            
            $pdfFiles[] = [
                'name' => $name,
                'file' => $relativePath
            ];
        }
    }
    
    // Ordenar alfabéticamente por nombre
    usort($pdfFiles, function($a, $b) {
        return strcasecmp($a['name'], $b['name']);
    });
    
    return $pdfFiles;
}

// Obtener PDFs de ambas carpetas
$catalogos = getPDFsFromDirectory(__DIR__ . '/assets/files/Catalogos', './assets/files/Catalogos/');
$infoTecnica = getPDFsFromDirectory(__DIR__ . '/assets/files/Info-Tecnica', './assets/files/Info-Tecnica/');

// Crear el array de respuesta
$response = [
    'catalogos' => $catalogos,
    'infoTecnica' => $infoTecnica
];

echo json_encode($response);
?>