<?php

    session_start();

    require_once 'db/config.php';

        $username = 'admin';
        $firstname = 'ioannis';
        $lastname = 'zacheilas';
        $email = 'admin@admin.gr';
        $password = '1234';
        $role = 'admin';




                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (username, firstname, lastname, role, email, password)
                        VALUES (:username, :firstname, :lastname, :role, :email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $username);            
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':role', $role);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->execute();

                echo 'Η εγγραφή έγινε';
?>