<?php
$title = "Student Dashboard"; // Set the page title
?>

<div class="welcome">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h1>
    <p>Here you can manage your profile, view your courses, attendance, and results.</p>
</div>

<div class="card-box">
    <h2>Your Courses</h2>
    <p>Check your enrolled course and progress.</p>
    <a href="/student/course" style="color: #007bff; text-decoration: underline;">View Courses</a>
</div>
