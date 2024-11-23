<?php
// api.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

// Incluir configuración de los archivos JSON
require_once 'config.php';

// Función para leer el contenido de un archivo JSON y devolverlo como array
function leerArchivo($archivo) {
    if (!file_exists($archivo)) {
        return [];
    }
    $contenido = file_get_contents($archivo);
    return json_decode($contenido, true);
}

// Función para escribir un array en un archivo JSON
function escribirArchivo($archivo, $data) {
    $contenido = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($archivo, $contenido);
}

// Obtener las categorías
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['endpoint']) && $_GET['endpoint'] == 'categorias') {
    echo json_encode(leerArchivo(CATEGORIAS_FILE));
}

// Obtener todas las comidas
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['endpoint']) && $_GET['endpoint'] == 'comidas') {
    echo json_encode(leerArchivo(COMIDAS_FILE));
}

// Obtener una comida por ID
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['endpoint']) && $_GET['endpoint'] == 'comidas' && isset($_GET['id'])) {
    $comidas = leerArchivo(COMIDAS_FILE);
    $id = $_GET['id'];
    $comida = array_filter($comidas, function ($comida) use ($id) {
        return $comida['id'] == $id;
    });
    echo json_encode(array_values($comida)[0] ?? null);
}

// Obtener todas las bebidas
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['endpoint']) && $_GET['endpoint'] == 'bebidas') {
    echo json_encode(leerArchivo(BEBIDAS_FILE));
}

// Obtener una bebida por ID
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['endpoint']) && $_GET['endpoint'] == 'bebidas' && isset($_GET['id'])) {
    $bebidas = leerArchivo(BEBIDAS_FILE);
    $id = $_GET['id'];
    $bebida = array_filter($bebidas, function ($bebida) use ($id) {
        return $bebida['id'] == $id;
    });
    echo json_encode(array_values($bebida)[0] ?? null);
}

// Agregar una comida
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['endpoint']) && $_GET['endpoint'] == 'comidas') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['titulo'], $data['descripcion'], $data['precio'], $data['imagen'], $data['categoria_id'])) {
        $comidas = leerArchivo(COMIDAS_FILE);
        $nuevoId = count($comidas) + 1;
        $data['id'] = $nuevoId;
        $comidas[] = $data;
        escribirArchivo(COMIDAS_FILE, $comidas);
        echo json_encode(['message' => 'Comida agregada correctamente']);
    } else {
        echo json_encode(['error' => 'Faltan parámetros']);
    }
}

// Agregar una bebida
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['endpoint']) && $_GET['endpoint'] == 'bebidas') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['titulo'], $data['descripcion'], $data['precio'], $data['imagen'], $data['cantidad'], $data['categoria_id'])) {
        $bebidas = leerArchivo(BEBIDAS_FILE);
        $nuevoId = count($bebidas) + 1;
        $data['id'] = $nuevoId;
        $bebidas[] = $data;
        escribirArchivo(BEBIDAS_FILE, $bebidas);
        echo json_encode(['message' => 'Bebida agregada correctamente']);
    } else {
        echo json_encode(['error' => 'Faltan parámetros']);
    }
}
?>
