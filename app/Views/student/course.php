<?php
$title = "My Courses"; // Set the page title
?>

<div class="welcome">
    <h1>My Course</h1>
    <p>Below is the course you are enrolled in:</p>
</div>

<?php if (!empty($courses)): ?>
    <?php foreach ($courses as $course): ?>
        <div class="card-box">
            <h2><?= htmlspecialchars($course['course_name']) ?></h2>
            <p><strong>Description:</strong> <?= htmlspecialchars($course['description']) ?></p>
            <!-- <p><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p> -->
            <p><strong>Enrollment Date:</strong> <?= htmlspecialchars($course['enrollment_date']) ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card-box">
        <p>No courses found. Please contact your administrator for enrollment.</p>
    </div>
<?php endif; ?>