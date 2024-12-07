<?php
// Display errors if there are any
if (!empty($errors)) {
    echo '<div class="error-text">';
    foreach ($errors as $error) {
        echo '<p>' . htmlspecialchars($error) . '</p>';
    }
    echo '</div>';
}

// Display Sucess if there are any
if (!empty($success)) {
    echo '<div class="success-text">';
    foreach ($success as $succes) {
        echo '<p>' . htmlspecialchars($succes) . '</p>';
    }
    echo '</div>';
}
?>
