<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "chris"; // Replace with your database password
$dbname = "FeedbackDB";

// Telegram bot configuration
$botToken = "7482300969:AAGT6AowlsKASk_p7_ThjiwVtM4v-7DcY0I"; // Your bot token
$chatID = "1200701905"; // Replace with your chat ID

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $phone = $conn->real_escape_string($_POST["phone"]);
    $message = $conn->real_escape_string($_POST["message"]);

    // Insert into database
    $sql = "INSERT INTO feedback (name, email, phone, message) 
            VALUES ('$name', '$email', '$phone', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Prepare the feedback message
        $feedbackMessage = "You have received new feedback:\n\n" .
                           "Name: $name\n" .
                           "Email: $email\n" .
                           "Phone: $phone\n" .
                           "Message:\n$message";

        // Send feedback to Telegram using the bot
        $telegramUrl = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatID&text=" . urlencode($feedbackMessage);

        // Send the message to Telegram
        $response = file_get_contents($telegramUrl);

        if ($response) {
            echo "<script>alert('Thank you for your feedback! It has been submitted successfully.'); window.location.href='feedback.html';</script>";
        } else {
            echo "Feedback submitted, but Telegram message failed.";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
