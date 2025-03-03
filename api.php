<?php
header("Content-Type: application/json");
include 'db.php';

$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data["name"]) && isset($data["email"]) && isset($data["comments"])) {
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, comments) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data["name"], $data["email"], $data["comments"]);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Feedback submitted successfully"]);
        } else {
            echo json_encode(["error" => "Failed to submit feedback"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => "Invalid input data"]);
    }
} 
elseif ($request_method == "GET") {
    $sql = "SELECT id, name, email, comments, created_at FROM feedback ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $feedbacks = [];
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }

    echo json_encode($feedbacks);
} 
else {
    echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>