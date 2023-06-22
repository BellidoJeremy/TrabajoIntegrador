<?php
require 'database.php';

if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
  $user_id = $_POST['user_id'];
  $user = $_POST['user'];
  $email = $_POST['email'];
  $age = $_POST['age'];
  $number = $_POST['number'];

  // Actualizar el usuario en la base de datos
  $stmt = $conn->prepare('UPDATE users SET user = :user, email = :email, age = :age, number = :number WHERE id = :id');
  $stmt->bindParam(':user', $user);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':age', $age);
  $stmt->bindParam(':number', $number);
  $stmt->bindParam(':id', $user_id);

  if ($stmt->execute()) {
    // Redireccionar a users.php con mensaje de éxito
    header('Location: users.php?success=1');
    exit();
  } else {
    // Manejar el error en caso de que la actualización falle
    $error = 'Hubo un error al actualizar el usuario.';
  }
} else {
  // Redireccionar a users.php si no se proporcionó un ID de usuario válido
  header('Location: users.php');
  exit();
}
?>
