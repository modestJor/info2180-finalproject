<?php if (isset($_SESSION['user_id'])): ?>
<div class="sidebar">
    <a href="#" onclick="loadDashboard()">Dashboard</a>
    <?php if ($_SESSION['role'] === 'Admin'): ?>
    <a href="#" onclick="loadUsers()">Users</a>
    <a href="#" onclick="loadNewUser()">Add User</a>
    <?php endif; ?>
    <a href="#" onclick="loadNewContact()">Add Contact</a>
    <a href="#" onclick="logout()">Logout</a>
</div>
<?php endif; ?>