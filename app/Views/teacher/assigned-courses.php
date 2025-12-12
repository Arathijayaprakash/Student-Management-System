<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/css/style.css"> <!-- Add your CSS file here -->
</head>

<body>
    <div class="container">
        <h1>Assigned Courses</h1>

        <?php if (!empty($courses)): ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $index => $course): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($course['course_name']) ?></td>
                            <td><?= htmlspecialchars($course['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No courses assigned to you yet.</p>
        <?php endif; ?>

        <a href="/teacher/dashboard">Back to Dashboard</a>
    </div>
</body>

</html>