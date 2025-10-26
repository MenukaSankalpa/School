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
    "></div>

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
                    <th style="padding: 12px 16px; width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($admin = $result->fetch_assoc()): ?>
                <tr style="background: #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.04); border-radius: 8px;">
                    <td style="padding: 12px 16px; vertical-align: middle;"><?= $admin['id'] ?></td>
                    <td style="padding: 12px 16px;"><?= htmlspecialchars($admin['username']) ?></td>
                    <td style="padding: 12px 16px;"><?= htmlspecialchars($admin['email']) ?></td>
                    <!-- <td style="padding: 12px 16px; position:relative;">
                        <input type="password" value="<?= htmlspecialchars($admin['password']) ?>" readonly
                            style="width:80%; padding:8px 10px; border:1.5px solid #cbd5e1; border-radius:6px; font-size:14px;" />
                        <span class="material-icons toggle-pass" style="cursor:pointer; position:absolute; right:12px; top:8px; color:#555;">visibility</span>
                    </td>  -->
<td style="padding: 12px 16px;">
    <div style="display:flex; align-items:center; gap:6px;">
        <input type="password" value="<?= htmlspecialchars($admin['password']) ?>" readonly
            style="flex:1; padding:8px 10px; border:1.5px solid #cbd5e1; border-radius:6px; font-size:14px;" />
        <span class="material-icons toggle-pass" style="cursor:pointer; color:#555; font-size:20px;">
            visibility
        </span>
    </div>
</td> 







                    <td style="padding: 12px 16px;">
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <button type="button" class="edit-btn"
                                data-id="<?= $admin['id'] ?>"
                                data-username="<?= htmlspecialchars($admin['username']) ?>"
                                data-email="<?= htmlspecialchars($admin['email']) ?>"
                                data-password="<?= htmlspecialchars($admin['password']) ?>"
                                style="display: inline-flex; align-items: center; justify-content: center; 
                                       padding: 6px 12px; border-radius: 6px; border: 2px solid #2ecc71; 
                                       color: #2ecc71; font-weight: 600; font-size: 14px; 
                                       background: transparent; cursor: pointer;">
                                <span class="material-icons" style="font-size: 18px; margin-right: 6px;">edit</span> Edit
                            </button>

                            <!-- <a href="delete_admin.php?id=<?= $admin['id'] ?>" onclick="return confirm('Delete this admin?');"
                                style="display: inline-flex; align-items: center; justify-content: center; 
                                       padding: 6px 12px; border-radius: 6px; border: 2px solid #ef4444; 
                                       color: #ef4444; font-weight: 600; font-size: 14px; 
                                       text-decoration: none; cursor: pointer;">
                                <span class="material-icons" style="font-size: 18px; margin-right: 6px;">delete</span> Delete
                            </a> -->
                            <button type="button" class="delete-btn" 
    data-id="<?= $admin['id'] ?>" 
    style="display:inline-flex; align-items:center; justify-content:center;
           padding:6px 12px; border-radius:6px; border:2px solid #ef4444;
           color:#ef4444; font-weight:600; font-size:14px; background:transparent; cursor:pointer;">
    <span class="material-icons" style="font-size:18px; margin-right:6px;">delete</span> Delete
</button>

                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="margin-top: 20px; font-size: 16px;">No admins found.</p>
    <?php endif; ?>
</div>

<!-- Edit Admin Modal -->
<div id="editModal" style="
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.4);
  backdrop-filter: blur(6px);
  justify-content: center; align-items: center;
  z-index: 9999;">
  
  <div style="
    background: #fff;
    border-radius: 12px;
    padding: 30px 28px;
    width: 400px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    position: relative;
    animation: popIn 0.3s ease;">
    
    <span id="closeModal" style="
      position: absolute;
      top: 12px; right: 16px;
      font-size: 24px;
      cursor: pointer;
      color: #777;">&times;</span>

    <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 20px;">Edit Admin</h2>

    <form id="editAdminForm" autocomplete="off">
      <input type="hidden" name="id" id="editId">

      <label>Username</label>
      <input type="text" name="username" id="editUsername" required
        style="width:100%; padding:10px 12px; margin-bottom:12px; border:1.5px solid #cbd5e1; border-radius:6px;">

      <label>Email</label>
      <input type="email" name="email" id="editEmail" required
        style="width:100%; padding:10px 12px; margin-bottom:12px; border:1.5px solid #cbd5e1; border-radius:6px;">

      <label>Password</label>
      <input type="text" name="password" id="editPassword" required
        style="width:100%; padding:10px 12px; margin-bottom:18px; border:1.5px solid #cbd5e1; border-radius:6px;">

      <button type="submit" style="
        width:100%; padding:10px 16px;
        background:#2ecc71; color:#fff;
        border:none; border-radius:6px;
        font-weight:600; cursor:pointer;">Save Changes</button>
    </form>
  </div>
</div>

<!-- Delete Admin Modal -->
<div id="deleteModal" style="
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.4);
  backdrop-filter: blur(6px);
  justify-content: center; align-items: center;
  z-index: 9999;">
  
  <div style="
    background: #fff;
    border-radius: 12px;
    padding: 30px 28px;
    width: 400px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    position: relative;
    text-align:center;
    animation: popIn 0.3s ease;">
    
    <span id="closeDeleteModal" style="
      position: absolute;
      top: 12px; right: 16px;
      font-size: 24px;
      cursor: pointer;
      color: #777;">&times;</span>

    <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 20px;">Delete Admin</h2>
    <p style="margin-bottom: 24px;">Are you sure you want to delete this admin?</p>
    
    <button id="confirmDelete" style="
        padding: 10px 16px;
        background:#ef4444; color:#fff;
        border:none; border-radius:6px;
        font-weight:600; cursor:pointer; margin-right:10px;">Delete</button>
    <button id="cancelDelete" style="
        padding: 10px 16px;
        background:#bbb; color:#fff;
        border:none; border-radius:6px;
        font-weight:600; cursor:pointer;">Cancel</button>

    <input type="hidden" id="deleteId">
  </div>
</div>


<style>
@keyframes popIn { from {transform: scale(0.9); opacity: 0;} to {transform: scale(1); opacity: 1;} }
</style>

<script>
// Create Admin
document.getElementById('createAdminForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch('add_admin.php',{method:'POST',body:formData})
    .then(r=>r.text())
    .then(()=>{ 
        const toast = document.getElementById('toast');
        toast.textContent = '✅ Admin created successfully!';
        toast.style.display = 'block';
        setTimeout(()=>location.reload(),1500);
    });
});

// Toggle Password Visibility
document.querySelectorAll('.toggle-pass').forEach(icon=>{
    icon.addEventListener('click', function(){
        const input=this.previousElementSibling;
        if(input.type==='password'){ input.type='text'; this.textContent='visibility_off'; this.style.color='#2ecc71'; }
        else{ input.type='password'; this.textContent='visibility'; this.style.color='#555'; }
    });
});

// Edit Modal
const modal = document.getElementById('editModal');
const closeModal = document.getElementById('closeModal');
document.querySelectorAll('.edit-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        document.getElementById('editId').value = btn.dataset.id;
        document.getElementById('editUsername').value = btn.dataset.username;
        document.getElementById('editEmail').value = btn.dataset.email;
        document.getElementById('editPassword').value = btn.dataset.password;
        modal.style.display='flex';
    });
});
closeModal.onclick = ()=> modal.style.display='none';
window.onclick = e => { if(e.target===modal) modal.style.display='none'; };

// Delete Modal Logic
const deleteModal = document.getElementById('deleteModal');
const closeDeleteModal = document.getElementById('closeDeleteModal');
const cancelDelete = document.getElementById('cancelDelete');
let deleteId = null;

document.querySelectorAll('.delete-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        deleteId = btn.dataset.id;
        document.getElementById('deleteId').value = deleteId;
        deleteModal.style.display = 'flex';
    });
});

closeDeleteModal.onclick = cancelDelete.onclick = () => deleteModal.style.display='none';
window.onclick = e => { if(e.target===deleteModal) deleteModal.style.display='none'; };

// Confirm Delete AJAX
document.getElementById('confirmDelete').addEventListener('click', ()=>{
    const formData = new FormData();
    formData.append('id', deleteId);

    fetch('delete_admin.php', { method:'POST', body:formData })
    .then(r=>r.text())
    .then(()=> {
        deleteModal.style.display='none';
        const toast = document.getElementById('toast');
        toast.textContent = '✅ Admin deleted successfully!';
        toast.style.display='block';
        setTimeout(()=>location.reload(),1500);
    });
});


// Save Edited Admin
document.getElementById('editAdminForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch('update_admin.php',{method:'POST',body:formData})
    .then(r=>r.text())
    .then(()=>{ 
        modal.style.display='none';
        const toast = document.getElementById('toast');
        toast.textContent = '✅ Admin updated successfully!';
        toast.style.display='block';
        setTimeout(()=>location.reload(),1500);
    });
});
</script>
