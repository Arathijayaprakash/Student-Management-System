<h2 style="color:#007bff; margin-bottom:20px;">Add New Student</h2>

<form method="POST" action="/student/add" style="
    background:white;
    padding:20px;
    border-radius:8px;
    max-width:500px;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
" enctype="multipart/form-data">

    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Full Name:</label>
        <input type="text" name="name" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
        ">
    </div>

    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Email:</label>
        <input type="email" name="email" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
        ">
    </div>

    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Course:</label>
        <select name="course_id" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
            background:white;
            font-size:14px;
        ">
            <option value="">Select Course</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= $course['id'] ?>"
                    <?= isset($student) && $student['course_id'] == $course['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($course['course_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="margin-bottom:20px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Password:</label>
        <input type="password" name="password" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
        ">
    </div>

    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Upload Profile Image:</label>
        <input type="file" name="photo" accept="image/*" style="
            width:100%;
            padding:8px;
            border:1px solid #ccc;
            border-radius:5px;
            background:white;
        ">
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
        Create Student
    </button>

</form>