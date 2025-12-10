<h2 style="color:#007bff; margin-bottom:20px;">Edit Teacher Details</h2>

<form method="POST" action="/teachers/update" style="
    background:white;
    padding:20px;
    border-radius:8px;
    max-width:500px;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
">

    <div style="margin-bottom:15px;">
        <input type="hidden" name="id" value="<?= $teacher['id'] ?>">

        <label style="display:block; font-weight:bold; margin-bottom:5px;">Full Name:</label>
        <input type="text" name="name" value="<?= $teacher['name'] ?>" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
        ">
    </div>

    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Email:</label>
        <input type="email" name="email" value="<?= $teacher['email'] ?>" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
        ">
    </div>

    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Phone:</label>
        <input type="phone" name="phone" value="<?= $teacher['phone'] ?>" required style="
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
        ">
    </div>

    <div style="margin-bottom:15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Qualification:</label>
        <input type="qualification" name="qualification" value="<?= $teacher['qualification'] ?>" required style="
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
        Save
    </button>

</form>