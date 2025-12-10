<?php
$title = "Teacher Dashboard"; // Set the page title
?>

<div class="welcome">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h1>
    <p>Here you can manage your courses and profile.</p>
</div>

<div class="card-box">
    <h2>Assigned Courses</h2>
    <p>View the courses assigned to you and manage them.</p>
    <a href="/teacher/courses" style="color: #007bff; text-decoration: underline;">View Assigned Courses</a>
</div>

<div class="card-box">
    <h2>Update Profile</h2>
    <p>Keep your profile information up to date.</p>
    <a href="/teacher/profile" style="color: #007bff; text-decoration: underline;">Edit Profile</a>
</div>

<div class="card-box">
    <h2>Change Password</h2>
    <p>Ensure your account is secure by updating your password regularly.</p>
    <a href="/teacher/change_password" style="color: #007bff; text-decoration: underline;">Change Password</a>
</div>