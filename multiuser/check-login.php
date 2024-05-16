<?php  

//login login 
session_start();
include "db_conn.php";

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);
    $role = test_input($_POST['role']);

    if (empty($username)) {
        header("Location: login-index.php?error=User Name is Required");
    } else if (empty($password)) {
        header("Location: login-index.php?error=Password is Required");
    } else {

        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password']) && $row['role'] == $role) {
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['username'] = $row['username'];

                header("Location: redirect.php");

            } else {
                header("Location: login-index.php?error=Incorrect Username or Password");
            }
        } else {
            header("Location: login-index.php?error=Incorrect Username or Password");
        }

    }
    
} else {
    header("Location: login-index.php");
}
