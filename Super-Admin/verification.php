<?php
function generate_random_word($length = 5) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $random_word = '';

    for ($i = 0; $i < $length; $i++) {
        $random_word .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $random_word;
}

// Example usage
$random_word = generate_random_word();
echo "Random Word: " . $random_word;
?>
