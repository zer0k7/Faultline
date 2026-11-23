<?php
require_once __DIR__ . '/../config.php';

$xml = isset($_POST['xml']) ? $_POST['xml'] : '';
$courses = [];
$message = '';
$sample = '<?xml version="1.0" encoding="UTF-8"?>
<catalog>
  <course>
    <code>CS101</code>
    <title>Introduction to Computer Science</title>
  </course>
  <course>
    <code>MATH201</code>
    <title>Discrete Mathematics</title>
  </course>
</catalog>';

if ($xml !== '') {
    libxml_use_internal_errors(true);
    $document = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOENT | LIBXML_DTDLOAD | LIBXML_DTDATTR);
    if ($document === false) {
        $message = 'The XML document could not be parsed.';
    } else {
        foreach ($document->course as $course) {
            $courses[] = [
                'code' => (string) $course->code,
                'title' => (string) $course->title,
            ];
        }
        $message = count($courses) . ' course records loaded.';
    }
}

$pageTitle = 'Course import';
$activeNav = 'admin';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Operations</span>
    <h1>Import a course list</h1>
    <p class="lede">Paste an XML catalog from the registry export tool. The preview shows the parsed rows.</p>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($message !== ''): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>
        <form class="form" method="post" action="">
            <div class="field">
                <label for="xml">Catalog XML</label>
                <textarea id="xml" name="xml" class="mono" rows="12"><?= $xml !== '' ? $xml : $sample ?></textarea>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Parse catalog</button>
            </div>
        </form>
    </div>
</div>

<?php if ($courses): ?>
    <div class="card section">
        <div class="card-head"><h2>Parsed courses</h2></div>
        <div class="card-body">
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Code</th><th>Title</th></tr></thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td data-label="Code"><?= $course['code'] ?></td>
                                <td data-label="Title"><?= $course['title'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
