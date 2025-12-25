<?php
session_start();

// Check if user is logged in and is Admin
if (!isset($_SESSION['user_id'])) {
    echo "<h1>Access Denied</h1><p>Please log in first.</p>";
    exit;
}

if ($_SESSION['role'] !== 'Admin') {
    echo "<h1>Access Denied</h1><p>Only administrators can add new users.</p>";
    exit;
}
?>
<div class="new-user-form">
    <h2>Add New User</h2>
    <form id="new-user-form">
        <div class="input-group">
            <label>First Name</label>
            <input type="text" name="firstname" required placeholder="Enter first name">
        </div>
        <div class="input-group">
            <label>Last Name</label>
            <input type="text" name="lastname" required placeholder="Enter last name">
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="Enter email address">
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" id="new-password" name="password" required placeholder="Enter password">
            <small class="password-hint">Must be at least 8 characters with uppercase, lowercase, and a number</small>
        </div>
        <div class="input-group">
            <label>Role</label>
            <select name="role">
                <option value="Member">Member</option>
                <option value="Admin">Admin</option>
            </select>
        </div>
        <button type="submit" id="save-user-btn">Save User</button>
    </form>
</div>