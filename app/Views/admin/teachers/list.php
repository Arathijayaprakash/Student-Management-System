    <h2>Teacher List</h2>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">

        <!-- Search Form -->
        <form method="GET" action="/teachers" style="margin-bottom: 20px;">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                placeholder="Search by name, email, or qualification"
                style="padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" style="padding: 8px 12px; background: #007bff; color: white; border: none; border-radius: 4px;">
                Search
            </button>
            <!-- Clear Search Button -->
            <?php if (!empty($search)): ?>
                <a href="/teachers" style="padding: 6px 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                    Clear Search
                </a>
            <?php endif; ?>
        </form>
        <a href="/teachers/add"
            style="padding: 6px 12px; background: #28a745; color: white; border-radius: 4px; text-decoration: none;">
            + Add New Teacher
        </a>
    </div>

    <?php if (!empty($teachers)): ?>
        <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead style="background-color: #007bff; color: white;">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Qualification</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?= htmlspecialchars($teacher['id']) ?></td>
                        <td><?= htmlspecialchars($teacher['name']) ?></td>
                        <td><?= htmlspecialchars($teacher['email']) ?></td>
                        <td><?= htmlspecialchars($teacher['phone']) ?></td>
                        <td><?= htmlspecialchars($teacher['qualification']) ?></td>
                        <td>
                            <!-- Edit Button -->
                            <a href="/teachers/edit?id=<?= $teacher['id'] ?>"
                                style="background:#ffc107; padding:6px 12px; color:black; text-decoration:none; border-radius:4px;">
                                Edit
                            </a>

                            <!-- Delete Button -->
                            <a href="/teachers/delete?id=<?= $teacher['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this student?');"
                                style="background:#dc3545; padding:6px 12px; color:white; text-decoration:none; border-radius:4px; margin-left:6px;">
                                Delete
                            </a>

                            <!-- Assign Course Button -->
                            <a href="/assign-courses?id=<?= $teacher['id'] ?>"
                                style="background:#28a745; padding:6px 12px; color:white; text-decoration:none; border-radius:4px; margin-left:6px;">
                                Assign Course
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if ($totalPages > 1): ?>
            <div style="margin-top: 20px; text-align:center;">

                <!-- Previous Page -->
                <?php if ($page > 1): ?>
                    <a href="/teachers?page=<?= $page - 1 ?>&search=<?= urldecode($search) ?>"
                        style="padding:8px 14px; background:#007bff; color:white; text-decoration:none; border-radius:4px;">
                        Prev
                    </a>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/teachers?page=<?= $i ?>&search=<?= urldecode($search) ?>"
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
                    <a href="/teachers?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>"
                        style="padding:8px 14px; background:#007bff; color:white; text-decoration:none; border-radius:4px;">
                        Next
                    </a>
                <?php endif; ?>

            </div>
        <?php endif; ?>

    <?php else: ?>
        <p>No Teachers found.</p>
    <?php endif; ?>