<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (isset($_POST['search'])) {
    $search = trim(filter_var($_POST['search'], FILTER_SANITIZE_STRING));

    if (!empty($search)) {
        $search = "%$search%";

        $statement = $pdo->query("SELECT profile_name, profile_image
        FROM users WHERE profile_name LIKE ? LIMIT 15");

        $statement->execute([$search]);

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $result = "No users";
    }

    if (empty($result)) {
        $result = "No users";
    }

    header('Content-Type: application/json');

    echo json_encode($result);
}
