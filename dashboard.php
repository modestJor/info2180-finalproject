<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please log in.";
    exit();
}

$host = 'localhost';
$db   = 'dolphin_crm';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$stmt = $pdo->prepare("SELECT id, title, firstname, lastname, email, company, type, created_at, updated_at FROM Contacts ORDER BY updated_at DESC");
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>

<div class="dashboard">
    <h2>Dashboard</h2>

    <h3>Contacts</h3>

    <table class="contacts-table" width="100%" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Type</th>
                <th>Updated</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($contacts) === 0): ?>
                <tr>
                    <td colspan="6">No contacts found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= e($contact['title'] . '. ' . $contact['firstname'] . ' ' . $contact['lastname']) ?></td>
                        <td><?= e($contact['email']) ?></td>
                        <td><?= e($contact['company']) ?></td>
                        <td><?= e($contact['type']) ?></td>
                        <td><?= e($contact['updated_at']) ?></td>
                        <td>
                            <button onclick="loadContactDetails(<?= (int)$contact['id'] ?>)">View</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
