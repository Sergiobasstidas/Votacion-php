<?php
$host = "localhost";
$port = "5432";
$dbname = "votacion";
$user = "tu_usuario";
$password = "tu_contraseña";

// Crear conexión
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Conexión fallida: " . pg_last_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'loadData') {
        // Cargar regiones y candidatos
        $regionesResult = pg_query($conn, "SELECT id, nombre FROM regiones");
        $candidatosResult = pg_query($conn, "SELECT id, nombre FROM candidatos");

        $regiones = pg_fetch_all($regionesResult);
        $candidatos = pg_fetch_all($candidatosResult);

        $response = array(
            'regiones' => $regiones,
            'candidatos' => $candidatos
        );

        echo json_encode($response);
    } elseif ($action == 'loadComunas') {
        // Cargar comunas según la región seleccionada
        $regionId = $_POST['regionId'];
        $comunasResult = pg_query($conn, "SELECT id, nombre FROM comunas WHERE region_id = $regionId");

        $comunas = pg_fetch_all($comunasResult);

        echo json_encode($comunas);
    } elseif ($action == 'submitVote') {
        // Validar y registrar voto
        $nombreApellido = $_POST['nombreApellido'];
        $alias = $_POST['alias'];
        $rut = $_POST['rut'];
        $email = $_POST['email'];
        $region = $_POST['region'];
        $comuna = $_POST['comuna'];
        $candidato = $_POST['candidato'];
        $enterado = $_POST['enterado'];

        // Verificar duplicación de votos por RUT
        $result = pg_query($conn, "SELECT id FROM votos WHERE rut = '$rut'");
        if (pg_num_rows($result) > 0) {
            echo "El RUT ya ha sido registrado.";
        } else {
            // Insertar voto en la base de datos
            $query = "INSERT INTO votos (nombre_apellido, alias, rut, email, region_id, comuna_id, candidato_id, enterado_por)
                      VALUES ('$nombreApellido', '$alias', '$rut', '$email', $region, $comuna, $candidato, '$enterado')";
            $result = pg_query($conn, $query);

            if ($result) {
                echo "success";
            } else {
                echo "Error: " . pg_last_error($conn);
            }
        }
    }
}

pg_close($conn);
?>
