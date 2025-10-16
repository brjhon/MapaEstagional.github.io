<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$configFile = 'radar-config.json';

// GET - Retorna a configuração
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($configFile)) {
        echo file_get_contents($configFile);
    } else {
        // Configuração padrão
        $defaultConfig = [
            'radar1' => [
                'name' => 'Radar Mendanha',
                'url' => 'https://plataforma-clima.dados.rio/radar/mendanha/reflectivity/mapa',
                'autoRefresh' => true,
                'refreshInterval' => 300000
            ],
            'radar2' => [
                'name' => 'Radar Alerta Rio',
                'url' => 'https://www.sistema-alerta-rio.com.br/upload/Mapa/mapaRadar.html',
                'autoRefresh' => true,
                'refreshInterval' => 300000
            ],
            'global' => [
                'activeRadar' => 'radar1',
                'showStatusBar' => true
            ],
            'lastUpdate' => time()
        ];
        echo json_encode($defaultConfig);
    }
}

// POST - Salva a configuração
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $config = json_decode($input, true);
    
    if ($config) {
        $config['lastUpdate'] = time();
        file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
        echo json_encode(['success' => true, 'lastUpdate' => $config['lastUpdate']]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    }
}
?>