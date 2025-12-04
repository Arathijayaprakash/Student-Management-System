<h2>Student List</h2>

<?php if (!empty($students)): ?>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <thead style="background-color: #007bff; color: white;">
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['id']) ?></td>
                    <td>
                        <?php if (!empty($student['photo'])): ?>
                            <img src="/uploads/students/<?= htmlspecialchars($student['photo']) ?>"
                                alt="Photo" width="60" height="60"
                                style="object-fit: cover; border-radius: 5px;">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><?= htmlspecialchars($student['course']) ?></td>
                    <td><?= htmlspecialchars($student['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No students found.</p>
<?php endif; ?>