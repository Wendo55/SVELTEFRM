
<?php
// deleteAccount.php
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
    echo json_encode(['status' => 'error', 'message' => 'Correo es requerido']);
    exit;
}

try {
    // Conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Eliminar el usuario
    $query = "DELETE FROM Usuario WHERE correo = :correo";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Cuenta eliminada con éxito']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $e->getMessage()]);
}
?>