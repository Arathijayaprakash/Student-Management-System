    <h2>Course List</h2>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <!-- Search Form -->
        <form method="GET" action="/courses" style="margin-bottom: 20px;">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                placeholder="Search by course"
                style="padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" style="padding: 8px 12px; background: #007bff; color: white; border: none; border-radius: 4px;">
                Search
            </button>
            <!-- Clear Search Button -->
            <?php if (!empty($search)): ?>
                <a href="/courses" style="padding: 6px 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                    Clear Search
                </a>
            <?php endif; ?>
        </form>
        <a href="/course/add"
            style="padding: 8px 14px; background: #28a745; color: white; border-radius: 4px; text-decoration: none;">
            + Add New Course
        </a>
    </div>

    <table border="1" cellpadding="10" cellspacing="0" width="100%"
        style="border-collapse: collapse; text-align: left;">
        <thead style="background: #f2f2f2;">
            <tr>
                <th>ID</th>
                <th>Course Name</th>
                <th>Description</th>
                <th>Duration</th>
                <th style="width: 120px;">Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= $course['id'] ?></td>
                        <td><?= htmlspecialchars($course['course_name']) ?></td>
                        <td><?= htmlspecialchars($course['description']) ?></td>
                        <td><?= htmlspecialchars($course['duration']) . ' months' ?></td>

                        <td>
                            <a href="/admin/course/edit?id=<?= $course['id'] ?>"
                                style="padding: 5px 10px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                                Edit
                            </a>

                            <a href="/admin/course/delete?id=<?= $course['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this course?');"
                                style="padding: 5px 10px; background: #dc3545; color: white; text-decoration: none; border-radius: 4px;">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">
                        No courses found.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
        <div style="margin-top: 20px; text-align:center;">

            <!-- Previous Page -->
            <?php if ($page > 1): ?>
                <a href="/courses?page=<?= $page - 1 ?>&search=<?= urldecode($search) ?>"
                    style="padding:8px 14px; background:#007bff; color:white; text-decoration:none; border-radius:4px;">
                    Prev
                </a>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="/courses?page=<?= $i ?>&search=<?= urldecode($search) ?>"
                    style="
                    padding:8px 14px;
                    margin:0 3px;
                    border-radius:4px;
                    text-decoration:none;
                    <?= $i == $page ? 'background:#0056b3;color:white;' : 'background:#e2e6ea;color:black;' ?>
                ">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- Next Page -->
            <?php if ($page < $totalPages): ?>
                <a href="/courses?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>"
                    style="padding:8px 14px; background:#007bff; color:white; text-decoration:none; border-radius:4px;">
                    Next
                </a>
            <?php endif; ?>

        </div>
    <?php endif; ?>