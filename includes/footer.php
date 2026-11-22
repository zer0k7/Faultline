</main>
<footer class="site-footer">
    <div class="footer-inner">
        <div>
            <h2>Faultline University</h2>
            <p>A fictional campus portal used for security training. Every record in this system is invented.</p>
            <p>Run the application on localhost only.</p>
        </div>
        <div>
            <h3>Campus</h3>
            <div class="footer-links">
                <a href="<?= url('courses.php') ?>">Courses</a>
                <a href="<?= url('faculty.php') ?>">Faculty</a>
                <a href="<?= url('news.php') ?>">News</a>
                <a href="<?= url('feedback.php') ?>">Feedback</a>
            </div>
        </div>
        <div>
            <h3>Training</h3>
            <div class="footer-links">
                <a href="<?= url('lab/') ?>">Lab Guide</a>
                <a href="<?= url('dashboard.php') ?>">Dashboard</a>
                <a href="<?= url('admin.php') ?>">Admin</a>
                <a href="<?= url('contact.php') ?>">Contact</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; <?= date('Y') ?> Faultline University</span>
        <span>Faultline is a fictional institution. Local training use only.</span>
    </div>
</footer>
</body>
</html>
