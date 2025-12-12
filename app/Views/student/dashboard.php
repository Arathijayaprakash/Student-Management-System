<?php
$title = "Student Dashboard"; // Set the page title
?>

<div class="welcome">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h1>
    <p>Here you can manage your profile.</p>
</div>

<div class="card-box">
    <h2>Update Profile</h2>
    <p>Keep your profile information up to date.</p>
    <a href="/student/profile" style="color: #007bff; text-decoration: underline;">Edit Profile</a>
</div>
<div class="card-box">
    <h2>Your Courses</h2>
    <p>Check your enrolled course and progress.</p>
    <a href="/student/course" style="color: #007bff; text-decoration: underline;">View Courses</a>
</div>
<div class="card-box">
    <h2>Change Password</h2>
    <p>Ensure your account is secure by updating your password regularly.</p>
    <a href="/student/change_password" style="color: #007bff; text-decoration: underline;">Change Password</a>
</div>