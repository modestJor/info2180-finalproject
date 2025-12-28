<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<h1>Access Denied</h1><p>Please log in first.</p>";
    exit;
}

require 'db_connect.php';

// Fetch all users for "Assigned To" dropdown
$usersStmt = $pdo->prepare("SELECT id, firstname, lastname FROM Users ORDER BY firstname");
$usersStmt->execute();
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<div class="new-contact-form">
    <div class="top-bar">
        Welcome, <?= e($_SESSION['firstname'] ?? ''); ?> |
        <a href="#" onclick="loadDashboard(); return false;">Dashboard</a> |
        <a href="#" onclick="logout(); return false;">Logout</a>
    </div>
    
    <h2>Add New Contact</h2>
    <form id="new-contact-form">
        <div class="input-group">
            <label>Title</label>
            <select name="title" required>
                <option value="">Select Title</option>
                <option value="Mr.">Mr.</option>
                <option value="Mrs.">Mrs.</option>
                <option value="Ms.">Ms.</option>
                <option value="Dr.">Dr.</option>
                <option value="Prof.">Prof.</option>
            </select>
        </div>
        
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
            <label>Telephone</label>
            <input type="tel" name="telephone" placeholder="Enter telephone number">
        </div>
        
        <div class="input-group">
            <label>Company</label>
            <input type="text" name="company" placeholder="Enter company name">
        </div>
        
        <div class="input-group">
            <label>Type</label>
            <select name="type" required>
                <option value="">Select Type</option>
                <option value="Sales Lead">Sales Lead</option>
                <option value="Support">Support</option>
            </select>
        </div>
        
        <div class="input-group">
            <label>Assigned To</label>
            <select name="assigned_to">
                <option value="">Unassigned</option>
                <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>">
                    <?= e($user['firstname'] . ' ' . $user['lastname']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" id="save-contact-btn">Save Contact</button>
            <button type="button" onclick="loadDashboard()">Cancel</button>
        </div>
    </form>
</div>