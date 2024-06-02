<?php
require_once('../../config.php');
require_once('lib.php');

require_login();

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$text = $input['text'] ?? '';

if ($text) {
    // Output the received text to the console for debugging
    error_log("Received Text: " . $text);

    // Perform readability score calculation here
    // Example: $score = readability_score($text);

    // For debugging, you can also output the calculated score
    // Return the score as JSON
    echo json_encode(['score' => $score]);
} else {
    echo json_encode(['error' => 'No text provided']);
}
