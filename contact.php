<?php
require_once __DIR__ . '/config.php';
session_start();

$confirmation = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    if ($conn) {
        mysqli_query($conn, "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$message')");
    }
    $confirmation = "Thank you, $name. The help desk will reply to $email.";
}

$messages = $conn ? mysqli_query($conn, 'SELECT * FROM contact_messages ORDER BY id DESC LIMIT 5') : false;

$pageTitle = 'Contact';
$activeNav = 'contact';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Help desk</span>
    <h1>Contact the student office</h1>
    <p class="lede">Questions about records, fees or course changes. The desk answers on weekdays.</p>
</div>

<div class="split">
    <div class="card">
        <div class="card-body">
            <?php if ($confirmation !== ''): ?>
                <div class="alert alert-success"><?= $confirmation ?></div>
            <?php endif; ?>
            <form class="form" method="post" action="">
                <div class="form-grid">
                    <div class="field">
                        <label for="name">Your name</label>
                        <input id="name" name="name" type="text" autocomplete="name" required>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required>
                    </div>
                </div>
                <div class="field">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Send message</button>
                </div>
            </form>
        </div>
    </div>

    <aside class="sidebar">
        <div class="card">
            <div class="card-body">
                <h3>Office hours</h3>
                <dl class="kv">
                    <dt>Monday to Thursday</dt><dd>09:00 to 16:00</dd>
                    <dt>Friday</dt><dd>09:00 to 13:00</dd>
                    <dt>Email</dt><dd>helpdesk@faultline.test</dd>
                </dl>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Recent messages</h3>
                <?php if ($messages && mysqli_num_rows($messages) > 0): ?>
                    <div class="stack">
                        <?php while ($row = mysqli_fetch_assoc($messages)): ?>
                            <div>
                                <div class="small muted"><?= $row['name'] ?> | <?= $row['created_at'] ?></div>
                                <p class="small"><?= $row['message'] ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="small muted">No messages yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </aside>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
