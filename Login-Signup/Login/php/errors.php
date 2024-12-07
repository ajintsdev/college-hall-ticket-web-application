<?php
// Display errors if there are any
if (!empty($errors)) {
    echo '<div class="error-text">';
    foreach ($errors as $error) {
        echo '<p>' . htmlspecialchars($error) . '</p>';
    }
    echo '</div>';
}
?>
