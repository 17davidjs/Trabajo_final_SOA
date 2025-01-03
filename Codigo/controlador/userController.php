<?php
require_once __DIR__ . 'Codigo/modelo/userModel.php';

class userController
{
    private $userModel;

    public function __construct($connection)
    {
        $this->userModel = new userModel($connection);
    }

    public function index()
    {
        $users = $this->userModel->getAllUsers();

        // Asegúrate de que `$users` no sea null antes de cargar la vista
        if (empty($users))
        {
            $users = []; // Define `$users` como un array vacío si no hay datos
        }

        // Depuración
        echo '<pre>';
        var_dump($users);
        echo '</pre>';

        require_once __DIR__ . 'Codigo/vista/usuarios/index_usuarios.php';
    }


    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellidos' => $_POST['apellidos'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                'direccion' => $_POST['direccion'],
                'correo_electronico' => $_POST['correo_electronico'],
                'telefono' => $_POST['telefono'],
                'usuario' => $_POST['telefono'],
                'contrasena' => $_POST['contrasena'],
                'role' => $_POST['role']
            ];
            $this->userModel->createUser($data);
            header('Location: Codigo/vista/usuarios/index_usuarios.php');
        }
        else
        {
            require_once __DIR__ . 'Codigo/vista/usuarios/create.php';
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellidos' => $_POST['apellidos'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                'direccion' => $_POST['direccion'],
                'correo_electronico' => $_POST['correo_electronico'],
                'telefono' => $_POST['telefono'],
                'usuario' => $_POST['telefono'],
                'contrasena' => $_POST['contrasena'],
                'role' => $_POST['role']
            ];
            $this->userModel->updateUser($id, $data);
            header('Location: Codigo/vista/usuarios/index_usuarios.php');
        }
        else
        {
            $user = $this->userModel->getUserById($id);
            require_once __DIR__ . 'Codigo/vista/usuarios/edit.php';
        }
    }

    public function delete($id)
    {
        $this->userModel->deleteUser($id);
        header('Location: Codigo/vista/usuarios/index_usuarios.php');
    }
}
?>
