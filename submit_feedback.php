<?php
header("Content-Type: application/json");
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $comments = $_POST["comments"];

    if (!empty($name) && !empty($email) && !empty($comments)) {
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, comments) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $comments);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Feedback submitted successfully!"]);
        } else {
            echo json_encode(["error" => "Error submitting feedback"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => "All fields are required"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>