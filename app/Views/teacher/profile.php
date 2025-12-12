<h2 style="color:#007bff; margin-bottom:20px;">My Profile</h2>

<?php if (!empty($success)): ?>
    <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:20px;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<form method="POST" action="/teacher/profile/update" style="
    background:white;
    padding:20px;
    border-radius:8px;
    max-width:500px;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
" enctype="multipart/form-data">

    <div style="margin-bottom:15px;">
        <input type="hidden" name="id" value="<?= $teacher['id'] ?>">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Full Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($teacher['name']) ?>" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
        ">
    </div>

    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($teacher['email']) ?>" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
        ">
    </div>

    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Phone:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($teacher['phone']) ?>" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
        ">
    </div>
    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Qualification:</label>
        <input type="text" name="qualification" value="<?= htmlspecialchars($teacher['qualification']) ?>" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
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
        Save Changes
    </button>
</form>