<?php
require_once __DIR__ . '/../config.php';

$url = isset($_POST['url']) ? $_POST['url'] : '';
$message = '';
$slug = '';
$files = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $url !== '') {
    $data = @file_get_contents($url);
    if ($data === false) {
        $message = 'The package could not be downloaded.';
    } else {
        $tmp = tempnam(sys_get_temp_dir(), 'flt');
        file_put_contents($tmp, $data);
        $zip = new ZipArchive();
        if ($zip->open($tmp) === true) {
            $slug = 'theme-' . substr(md5($url), 0, 8);
            $destination = __DIR__ . '/../themes/' . $slug;
            if (!is_dir($destination)) {
                mkdir($destination, 0775, true);
            }
            $zip->extractTo($destination);
            $zip->close();
            $files = array_values(array_diff(scandir($destination), ['.', '..']));
            $message = 'Theme package installed.';
        } else {
            $message = 'The package is not a readable ZIP file.';
        }
        unlink($tmp);
    }
}

$pageTitle = 'Theme installer';
$activeNav = 'admin';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Appearance</span>
    <h1>Install a theme package</h1>
    <p class="lede">Install a theme from a package URL. The package is extracted into the themes directory.</p>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($message !== ''): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>
        <form class="form" method="post" action="">
            <div class="field">
                <label for="url">Package URL</label>
                <input id="url" name="url" type="text" value="<?= $url ?>" placeholder="https://themes.faultline.test/campus-slate.zip">
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Install theme</button>
            </div>
        </form>
    </div>
</div>

<?php if ($slug !== ''): ?>
    <div class="card section">
        <div class="card-head">
            <h2>Installed package</h2>
            <span class="badge badge-accent"><?= $slug ?></span>
        </div>
        <div class="card-body">
            <div class="pill-row">
                <?php foreach ($files as $file): ?>
                    <a class="badge" href="<?= url('themes/' . $slug . '/' . $file) ?>"><?= $file ?></a>
                <?php endforeach; ?>
            </div>
            <p class="small muted mt-16">Files are available under <span class="mono">themes/<?= $slug ?>/</span>.</p>
        </div>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
