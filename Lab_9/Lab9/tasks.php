
<?php
require 'db.php';
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {

    //Assignment Bonus 24/3/25

    //Old code: It always gets all tasks from the database.
    // No way to get just one task.

    // case 'GET':
    //     $stmt = $pdo->query("SELECT * FROM tasks");
    //     echo json_encode($stmt->fetchAll());
    //     break;
    // case 'POST':
    //     if (!isset($data['title']) || !isset($data['description'])) {
    //         echo json_encode(["success" => false, "message" => "Missing task details"]);
    //         exit;
    //     }
    //     $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
    //     $stmt->execute([$data['title'], $data['description']]);
    //     echo json_encode(["success" => true, "message" => "Task added"]);
    //     break;

    // New code:Now, we can get only ONE task if we add ?id=2 in the URL.
    // If we ask for tasks.php?id=2, we only get task #2 instead of everything.
    // If no ID is given, it still shows all tasks like before!

    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch());
        } else {
            $stmt = $pdo->query("SELECT * FROM tasks");
            echo json_encode($stmt->fetchAll());
        }
        break;


    //Assignment 24/3/25 
    //Old Code: It tries to update or delete a task without checking if the ID exists.
    // If the ID is wrong or missing, it still runs the query.

    // case 'PUT':
    //     if (!isset($data['id']) || !isset($data['status'])) {
    //         echo json_encode(["success" => false, "message" => "Missing task ID or status"]);
    //         exit;
    //     }
    //     try {
    //         $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    //         $stmt->execute([$data['status'], $data['id']]);
    //         echo json_encode(["success" => true, "message" => "Task updated"]);
    //     } catch (PDOException $e) {
    //         echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    //     }
    //     break;
    // case 'DELETE':
    //     if (!isset($data['id'])) {
    //         echo json_encode(["success" => false, "message" => "Missing task ID"]);
    //         exit;
    //     }
    //     $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    //     $stmt->execute([$data['id']]);
    //     echo json_encode(["success" => true, "message" => "Task deleted"]);
    //     break;

    //New Code: 
    // first, it checks if the task ID exists in the database.
    // If the ID is missing or wrong, it stops and says "Task ID not found!"
    // Only updates or deletes if the ID is real.

    case 'PUT':
        if (!isset($data['id']) || !isset($data['status'])) {
            echo json_encode(["success" => false, "message" => "Missing task ID or status"]);
            exit;
        }
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$data['id']]);
        if ($stmt->rowCount() === 0) {
            echo json_encode(["success" => false, "message" => "Task ID not found!"]);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $stmt->execute([$data['status'], $data['id']]);
        echo json_encode(["success" => true, "message" => "Task updated"]);
        break;

    case 'DELETE':
        if (!isset($data['id'])) {
            echo json_encode(["success" => false, "message" => "Missing task ID"]);
            exit;
        }
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$data['id']]);
        if ($stmt->rowCount() === 0) {
            echo json_encode(["success" => false, "message" => "Task ID not found!"]);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(["success" => true, "message" => "Task deleted"]);
        break;

    default:
        echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
