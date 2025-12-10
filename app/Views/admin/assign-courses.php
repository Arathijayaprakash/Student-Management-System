<div style="color:#007bff; margin-bottom:20px; font-size:24px; font-weight:bold;">Assign Courses to <?= htmlspecialchars($teacher['name']) ?></div>

<form method="POST" action="/assign-courses/assign" style="
    background:white;
    padding:20px;
    border-radius:8px;
    max-width:500px;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
">
    <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher['id']) ?>"> <!-- Pre-selected teacher -->

    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Select Courses:</label>
        <select name="course_ids[]" multiple required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
            background:white;
            font-size:14px;
            height:150px; /* Adjust height to show multiple options */
        ">
            <?php foreach ($courses as $course): ?>
                <option value="<?= htmlspecialchars($course['id']) ?>"><?= htmlspecialchars($course['course_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <small style="display:block; margin-top:5px; color:#6c757d;">Hold down the Ctrl (Windows) or Command (Mac) key to select multiple courses.</small>
    </div>

    <button type="submit" style="
        background:#007bff;
        color:white;
        padding:12px 20px;
        border:none;
        cursor:pointer;
        border-radius:5px;
        font-size:16px;
        width:100%;
    ">
        Assign Course
    </button>
</form>