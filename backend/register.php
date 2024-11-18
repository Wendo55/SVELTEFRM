<?php
// register.php
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
$contrasena = $data['contrasena'];
$nombre = $data['nuevoNombre'];

if (!$correo || !$contrasena || !$nombre) {
    echo json_encode(['status' => 'error', 'message' => 'Por favor, completa todos los campos']);
    exit;
}

try {
    // Conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el correo ya está registrado
    $query = "SELECT * FROM Usuario WHERE correo = :correo";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'El correo ya está registrado']);
        exit;
    }

    // Insertar el nuevo usuario
    $query = "INSERT INTO Usuario (correo, clave, nombre) VALUES (:correo, :contrasena, :nombre)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contrasena', $contrasena);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Usuario registrado correctamente']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $e->getMessage()]);
}
?>
