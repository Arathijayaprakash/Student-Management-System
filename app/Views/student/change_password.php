<?php
$title = "Change Password"; // Set the page title
?>

<div class="welcome">
    <h1>Change Password</h1>
    <p>Update your account password below:</p>
</div>

<div class="card-box">
    <?php if (isset($error)): ?>
        <div style="color: red; margin-bottom: 15px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div style="margin-bottom: 15px;">
            <label for="current_password">Current Password:</label><br>
            <input type="password" id="current_password" name="current_password" required style="width: 100%; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="new_password">New Password:</label><br>
            <input type="password" id="new_password" name="new_password" required style="width: 100%; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="confirm_password">Confirm New Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required style="width: 100%; padding: 8px;">
        </div>

        <button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            Update Password
        </button>
    </form>
</div>