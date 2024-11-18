<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Conexión a la base de datos
$host = 'localhost';
$dbname = 'practica';
$username = 'root';
$password = '';

// Obtener los datos enviados
$data = json_decode(file_get_contents('php://input'), true);
$correo = $data['correo'];

if (!$correo) {
    echo json_encode(['status' => 'error', 'message' => 'Por favor, proporciona el correo']);
    exit;
}

try {
    // Conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el correo existe
    $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE correo = :correo");
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['status'] == 1) {
            // Desactivar la cuenta
            $updateQuery = "UPDATE Usuario SET status = 0 WHERE correo = :correo";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':correo', $correo);
            $updateStmt->execute();
            echo json_encode(['status' => 'success', 'message' => 'Cuenta desactivada con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'La cuenta ya está desactivada']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'El correo no está registrado']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $e->getMessage()]);
}