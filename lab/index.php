<?php
$pageTitle = 'Lab Guide';
$activeNav = 'lab';
require_once __DIR__ . '/../includes/header.php';

$categories = require __DIR__ . '/modules.php';
$total = 0;
foreach ($categories as $category) {
    $total += count($category['modules']);
}
?>
<div class="lab-hero">
    <div>
        <span class="eyebrow">Training layer</span>
        <h1>Faultline Lab Guide</h1>
        <p>Ten OWASP categories. Twenty four modules. Each module hides one flag behind a real weakness in the portal. Work from the outside in. The portal pages do not label the modules for you.</p>
    </div>
    <div class="lab-progress-box">
        <strong id="overall-count">0 / <?= $total ?></strong>
        <span>Modules solved in this browser</span>
        <div class="progress" aria-hidden="true"><span id="overall-bar"></span></div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="grid grid-4">
            <div>
                <h3>1. Explore</h3>
                <p class="small muted">Use the portal as a student. Read the pages, forms and API responses.</p>
            </div>
            <div>
                <h3>2. Find</h3>
                <p class="small muted">Look for trust boundaries. Ask what the server assumes about the client.</p>
            </div>
            <div>
                <h3>3. Exploit</h3>
                <p class="small muted">Test one idea at a time. Keep requests small and repeatable.</p>
            </div>
            <div>
                <h3>4. Capture</h3>
                <p class="small muted">Submit the flag here. Progress stays in localStorage on this browser.</p>
            </div>
        </div>
        <div class="lab-toolbar">
            <span class="small muted">Flags use the form FLT{...}. Hints are collapsed on purpose.</span>
            <button class="btn btn-secondary btn-sm" type="button" id="reset-progress">Reset progress</button>
        </div>
    </div>
</div>

<?php foreach ($categories as $category): ?>
    <?php $catTotal = count($category['modules']); ?>
    <section class="lab-category" data-category="<?= htmlspecialchars($category['id']) ?>" data-total="<?= $catTotal ?>">
        <div class="lab-category-head">
            <span class="badge badge-navy"><?= htmlspecialchars($category['id']) ?>:2025</span>
            <h2><?= htmlspecialchars($category['name']) ?></h2>
            <div class="progress" aria-hidden="true"><span id="cat-bar-<?= htmlspecialchars($category['id']) ?>"></span></div>
            <span class="small muted" id="cat-count-<?= htmlspecialchars($category['id']) ?>">0 / <?= $catTotal ?></span>
        </div>
        <p class="lab-category-meta"><?= htmlspecialchars($category['summary']) ?></p>
        <div class="lab-grid">
            <?php foreach ($category['modules'] as $module): ?>
                <article class="lab-module" data-module="<?= htmlspecialchars($module['key']) ?>">
                    <div class="lab-module-top">
                        <h3><?= htmlspecialchars($module['name']) ?></h3>
                        <span class="badge badge-<?= strtolower($module['difficulty']) ?>"><?= htmlspecialchars($module['difficulty']) ?></span>
                    </div>
                    <p><?= htmlspecialchars($module['objective']) ?></p>
                    <div class="lab-hints">
                        <?php foreach ($module['hints'] as $index => $hint): ?>
                            <details>
                                <summary>Hint <?= $index + 1 ?></summary>
                                <p><?= htmlspecialchars($hint) ?></p>
                            </details>
                        <?php endforeach; ?>
                    </div>
                    <form class="flag-form" data-module="<?= htmlspecialchars($module['key']) ?>">
                        <input type="hidden" name="module" value="<?= htmlspecialchars($module['key']) ?>">
                        <label class="sr-only" for="flag-<?= htmlspecialchars($module['key']) ?>">Flag for <?= htmlspecialchars($module['name']) ?></label>
                        <input id="flag-<?= htmlspecialchars($module['key']) ?>" type="text" name="flag" placeholder="FLT{...}" autocomplete="off" spellcheck="false">
                        <button class="btn btn-primary btn-sm" type="submit">Submit</button>
                    </form>
                    <div class="flag-status" data-status aria-live="polite"></div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endforeach; ?>

<script src="<?= url('assets/js/lab.js') ?>" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
