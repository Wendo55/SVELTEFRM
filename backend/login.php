<?php
// login.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Conexión a la base de datos
$host = 'localhost';
$dbname = 'practica';
$username = 'root'; // tu usuario
$password = ''; // tu contraseña

// Obtener datos del frontend (correo y contraseña)
$data = json_decode(file_get_contents('php://input'), true);
$correo = $data['correo'];
$contrasena = $data['contrasena'];

// Verificación de si los datos se reciben correctamente
if (!$correo || !$contrasena) {
    echo json_encode(['status' => 'error', 'message' => 'Por favor, ingresa correo y contraseña.']);
    exit;
}

// Crear una conexión PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el correo existe en la base de datos
    $query = "SELECT * FROM Usuario WHERE correo = :correo"; // Cambiado de "usuarios" a "Usuario"
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si el usuario existe y la contraseña es correcta (comparación directa)
    if ($user && $contrasena === $user['clave']) {
        echo json_encode(['status' => 'success', 'usuario' => $user['nombre'], 'correo' => $user['correo']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Credenciales incorrectas.']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $e->getMessage()]);
}
?>