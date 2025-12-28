<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "<h2>Access denied</h2><p>Please log in.</p>";
    exit;
}

require 'db_connect.php';

// Validate id
$contactId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$contactId) {
    http_response_code(400);
    echo "<h2>Invalid contact</h2>";
    exit;
}

// Fetch contact + created_by + assigned_to names
$stmt = $pdo->prepare("
    SELECT 
        c.*,
        CONCAT(cb.firstname, ' ', cb.lastname) AS created_by_name,
        CONCAT(at.firstname, ' ', at.lastname) AS assigned_to_name
    FROM Contacts c
    LEFT JOIN Users cb ON c.created_by = cb.id
    LEFT JOIN Users at ON c.assigned_to = at.id
    WHERE c.id = ?
");
$stmt->execute([$contactId]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contact) {
    http_response_code(404);
    echo "<h2>Contact not found</h2>";
    exit;
}

// Fetch notes for this contact
$notesStmt = $pdo->prepare("
    SELECT 
        n.comment,
        n.created_at,
        CONCAT(u.firstname, ' ', u.lastname) AS author_name
    FROM Notes n
    JOIN Users u ON n.created_by = u.id
    WHERE n.contact_id = ?
    ORDER BY n.created_at DESC
");
$notesStmt->execute([$contactId]);
$notes = $notesStmt->fetchAll(PDO::FETCH_ASSOC);

// Escape helper
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<div class="contact-details">
    <div class="contact-actions">
        <button onclick="handleAction(<?= $contact['id'] ?>, 'assign')">Assign to me</button>
        <button onclick="handleAction(<?= $contact['id'] ?>, 'switch')">
            Switch to <?= $contact['type'] === 'Sales Lead' ? 'Support' : 'Sales Lead' ?>
        </button>
    </div>

    <h2><?= e($contact['title'] . '. ' . $contact['firstname'] . ' ' . $contact['lastname']) ?></h2>

    <div class="contact-meta">
        <p><strong>Email:</strong> <?= e($contact['email']) ?></p>
        <p><strong>Telephone:</strong> <?= e($contact['telephone']) ?></p>
        <p><strong>Company:</strong> <?= e($contact['company']) ?></p>
        <p><strong>Type:</strong> <?= e($contact['type']) ?></p>
        <p><strong>Assigned To:</strong> <?= e($contact['assigned_to_name'] ?? 'Unassigned') ?></p>

        <p><strong>Created By:</strong> <?= e($contact['created_by_name'] ?? 'Unknown') ?></p>
        <p><strong>Date Created:</strong> <?= e($contact['created_at']) ?></p>
        <p><strong>Last Updated:</strong> <?= e($contact['updated_at']) ?></p>
    </div>

    <hr>

    <div class="notes-section">
        <h3>Notes</h3>

        <div id="notes-list">
            <?php if (count($notes) === 0): ?>
                <p class="muted">No notes yet.</p>
            <?php else: ?>
                <?php foreach ($notes as $n): ?>
                    <div class="note">
                        <div class="note-head">
                            <strong><?= e($n['author_name']) ?></strong>
                            <span class="note-date"><?= e($n['created_at']) ?></span>
                        </div>
                        <div class="note-body"><?= nl2br(e($n['comment'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <h4>Add a note</h4>
        <form id="add-note-form">
            <input type="hidden" name="contact_id" value="<?= (int)$contactId ?>">
            <textarea name="comment" id="note-comment" rows="4" required placeholder="Enter details here..."></textarea>
            <button type="submit">Add Note</button>
        </form>

        <p id="note-status" class="muted"></p>
    </div>
</div>
