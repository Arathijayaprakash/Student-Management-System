<p class="welcome">
    Welcome <?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?>
</p>

<!-- Dashboard Cards -->
<div style="display: flex; gap: 20px; margin-top: 20px;">

    <!-- Student Count Card -->
    <div style="
        flex: 1;
        padding: 20px;
        background: #007bff;
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    ">
        <h3 style="margin: 0; font-size: 24px;">Students</h3>
        <p style="margin: 10px 0; font-size: 36px; font-weight: bold;">
            <?=htmlspecialchars($studentCount); ?>
        </p>
    </div>

    <!-- Course Count Card -->
    <div style="
        flex: 1;
        padding: 20px;
        background: #28a745;
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    ">
        <h3 style="margin: 0; font-size: 24px;">Courses</h3>
        <p style="margin: 10px 0; font-size: 36px; font-weight: bold;">
            <?= 50 ?>
        </p>
    </div>

</div>