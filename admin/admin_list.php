<?php
include '../db.php';

// Fetch all admins (users with role = 2)
$sql = "SELECT * FROM users WHERE role = 2 ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!-- Google Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

<div id="admin-panel" style="font-family: 'Poppins', sans-serif; max-width: 1000px; margin: 40px auto; padding: 20px;">
    <h1 style="font-size: 26px; font-weight: 700; margin-bottom: 30px;">Manage Admins</h1>

    <!-- Toast Notification -->
    <div id="toast" style="
        display: none;
        position: fixed;
        top: 30px;
        right: 30px;
        background-color: #2ecc71;
        color: white;
        padding: 12px 20px;
        border-radius: 6px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        font-size: 14px;
        z-index: 9999;
    ">
        ✅ Admin created successfully!
    </div>

    <!-- Add Admin Form -->
    <div style="background: #fff; padding: 24px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 40px;">
        <h2 style="font-size: 20px; margin-bottom: 16px;">Add New Admin</h2>
        <form id="createAdminForm" autocomplete="off" style="display: flex; flex-wrap: wrap; gap: 16px;">
            <input type="text" name="username" placeholder="Username" required 
                style="flex: 1 1 200px; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 6px;" />
            <input type="email" name="email" placeholder="Email" required 
                style="flex: 1 1 240px; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 6px;" />
            <input type="text" name="password" placeholder="Password" required minlength="6" 
                style="flex: 1 1 200px; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 6px;" />
            <button type="submit"
                style="padding: 10px 24px; background-color: #2d6cdf; color: white; font-weight: 600; border: none; border-radius: 6px; cursor: pointer;">
                Create Admin
            </button>
        </form>
    </div>

    <!-- Admin Table -->
    <?php if ($result && $result->num_rows > 0): ?>
        <table style="width: 100%; border-collapse: separate; border-spacing: 0 12px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <thead>
                <tr style="background-color: #f1f5f9;">
                    <th style="padding: 12px 16px;">ID</th>
                    <th style="padding: 12px 16px;">Username</th>
                    <th style="padding: 12px 16px;">Email</th>
                    <th style="padding: 12px 16px;">Password</th>
                    <th style="padding: 12px 16px; width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($admin = $result->fetch_assoc()): ?>
                <tr style="background: #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.04); border-radius: 8px;">
                    <form action="update_admin.php" method="POST" autocomplete="off">
                        <td style="padding: 12px 16px; vertical-align: middle;"><?= $admin['id'] ?></td>
                        <td style="padding: 12px 16px;">
                            <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" 
                                required minlength="3" maxlength="50"
                                style="width: 100%; padding: 8px 10px; font-size: 14px; border: 1.5px solid #cbd5e1; border-radius: 6px;" />
                        </td>
                        <td style="padding: 12px 16px;">
                            <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" 
                                required
                                style="width: 100%; padding: 8px 10px; font-size: 14px; border: 1.5px solid #cbd5e1; border-radius: 6px;" />
                        </td>
                        <td style="padding: 12px 16px;">
                            <input type="text" name="password" value="<?= htmlspecialchars($admin['password']) ?>" 
                                required minlength="6"
                                style="width: 100%; padding: 8px 10px; font-size: 14px; border: 1.5px solid #cbd5e1; border-radius: 6px;" />
                        </td>
                        <td style="padding: 12px 16px;">
                            <div style="display: flex; flex-direction: column; gap: 12px; max-width: 140px;">
                                <input type="hidden" name="id" value="<?= $admin['id'] ?>" />
                                <button type="submit" title="Edit"
                                    style="display: inline-flex; align-items: center; justify-content: center; 
                                           padding: 8px 14px; border-radius: 6px; border: 2px solid #2ecc71; 
                                           color: #2ecc71; font-weight: 600; font-size: 14px; 
                                           background: transparent; cursor: pointer; transition: all 0.3s ease; width: 100%; box-sizing: border-box;">
                                    <span class="material-icons" style="font-size: 18px; margin-right: 8px;">edit</span> Edit
                                </button>
                                <a href="delete_admin.php?id=<?= $admin['id'] ?>" title="Delete" onclick="return confirm('Delete this admin?');"
                                    style="display: inline-flex; align-items: center; justify-content: center; 
                                           padding: 8px 14px; border-radius: 6px; border: 2px solid #ef4444; 
                                           color: #ef4444; font-weight: 600; font-size: 14px; 
                                           text-decoration: none; transition: all 0.3s ease; width: 100%; box-sizing: border-box;">
                                    <span class="material-icons" style="font-size: 18px; margin-right: 8px;">delete</span> Delete
                                </a>
                            </div>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="margin-top: 20px; font-size: 16px;">No admins found.</p>
    <?php endif; ?>
</div>

<!-- Hover Effects -->
<style>
    button[title="Edit"]:hover {
        background-color: #2ecc71;
        color: white;
        box-shadow: 0 4px 8px rgba(46, 204, 113, 0.4);
        border-color: #27ae60;
    }
    button[title="Edit"]:hover .material-icons {
        color: white;
    }

    a[title="Delete"]:hover {
        background-color: #ef4444;
        color: white;
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.4);
        border-color: #c0392b;
    }
    a[title="Delete"]:hover .material-icons {
        color: white;
    }
</style>

<!-- JavaScript for AJAX Create & Toast -->
<script>
    document.getElementById('createAdminForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch('add_admin.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            form.reset();

            const toast = document.getElementById('toast');
            toast.style.display = 'block';

            setTimeout(() => {
                toast.style.display = 'none';
                location.reload(); 
            }, 2000);
        })
        .catch(error => {
            alert("Error creating admin: " + error);
        });
    });
</script>
