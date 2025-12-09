<h2>Edit Course</h2>

<?php if (!empty($error)): ?>
    <div style="padding:10px; background:#f8d7da; color:#721c24; border-radius:5px; margin-bottom:15px;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<!-- Course Edit Form -->
<form action="/course/update" method="POST" style="max-width: 400px;">
    <!-- Course Name -->
    <input type="hidden" name="id" value="<?= $course['id'] ?>">
    <label for="course_name">Course Name</label><br>
    <input type="text" id="course_name" name="course_name" value="<?= $course['course_name'] ?>" required
        style="width:100%; padding:8px; margin:8px 0; border:1px solid #ccc; border-radius:4px;">

    <!-- Course Description -->
    <label for="description">Description</label><br>
    <textarea id="description" name="description" rows="4"
        style="width:100%; padding:8px; margin:8px 0; border:1px solid #ccc; border-radius:4px;"><?= htmlspecialchars($course['description']) ?></textarea>

    <!-- Duration -->
    <label for="duration">Duration (months)</label><br>
    <input type="number" id="duration" name="duration" value="<?= $course['duration'] ?>" min="1" required
        style="width:100%; padding:8px; margin:8px 0; border:1px solid #ccc; border-radius:4px;">

    <!-- Submit Button -->
    <button type="submit"
        style="padding:10px 15px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">
        Save Changes
    </button>
</form>