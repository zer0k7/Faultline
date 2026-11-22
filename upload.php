<?php
require_once __DIR__ . '/config.php';
session_start();

$message = '';
$uploaded = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $target = __DIR__ . '/uploads/' . $_FILES['file']['name'];
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        $message = 'Your file was uploaded.';
        $uploaded = 'uploads/' . $_FILES['file']['name'];
    } else {
        $message = 'The upload did not complete.';
    }
}

$pageTitle = 'Upload';
$activeNav = 'upload';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Profile media</span>
    <h1>Profile photo and documents</h1>
    <p class="lede">Upload a photo for your student card or attach a supporting document.</p>
</div>

<div class="page-narrow">
    <div class="card">
        <div class="card-body">
            <?php if ($message !== ''): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>
            <?php if ($uploaded !== ''): ?>
                <p class="small">Stored at <a href="<?= url($uploaded) ?>"><?= $uploaded ?></a></p>
                <?php if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $uploaded)): ?>
                    <img class="upload-preview" src="<?= url($uploaded) ?>" alt="Uploaded profile file">
                <?php endif; ?>
            <?php endif; ?>
            <form class="form" method="post" action="" enctype="multipart/form-data">
                <div class="field">
                    <label for="file">Choose a file</label>
                    <input id="file" name="file" type="file" required>
                    <span class="help">JPG, PNG or PDF for card photos. Maximum 32 MB.</span>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Upload file</button>
                    <a class="btn btn-secondary" href="<?= url('uploads/') ?>">Open upload folder</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
