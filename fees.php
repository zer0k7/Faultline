<?php
require_once __DIR__ . '/config.php';
session_start();

$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 5;
$message = '';
$flag = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fee_id'])) {
    $feeId = (int) $_POST['fee_id'];
    $amount = (float) $_POST['amount'];
    $coupon = $_POST['coupon'];
    $method = $_POST['method'];
    $discount = 0;

    if ($coupon !== '') {
        $result = mysqli_query($conn, "SELECT * FROM coupons WHERE code='$coupon'");
        if ($result && mysqli_num_rows($result) === 1) {
            $couponRow = mysqli_fetch_assoc($result);
            $discount = (int) $couponRow['discount_percent'];
            mysqli_query($conn, "UPDATE coupons SET uses = uses + 1 WHERE code='$coupon'");
        }
    }

    $finalAmount = $amount - ($amount * $discount / 100);
    $sql = "INSERT INTO payments (user_id, fee_id, amount, method, coupon) VALUES ($userId, $feeId, $finalAmount, '$method', '$coupon')";

    if (mysqli_query($conn, $sql)) {
        if ($coupon !== '') {
            $countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM payments WHERE user_id=$userId AND coupon='$coupon'");
            $countRow = $countResult ? mysqli_fetch_assoc($countResult) : null;
            if ($countRow && (int) $countRow['total'] > 1) {
                $flag = flag_value('logic.txt');
            }
        }

        if ($method === 'error') {
            trigger_error('Payment gateway timeout', E_USER_WARNING);
            $message = 'The payment was recorded but the fee status did not update.';
        } else {
            mysqli_query($conn, "UPDATE fees SET status='paid' WHERE id=$feeId");
            $message = 'Payment posted to the fee account.';
        }
    } else {
        $message = 'The payment could not be posted.';
    }
}

$fees = $conn ? mysqli_query($conn, "SELECT * FROM fees WHERE user_id=$userId ORDER BY id") : false;

$pageTitle = 'Fees';
$activeNav = 'dashboard';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Finance</span>
    <h1>Fee account</h1>
    <p class="lede">Charges for the current term, payment status and the payment form.</p>
</div>

<?php if ($message !== ''): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>
<?php if ($flag !== ''): ?>
    <div class="alert alert-success">
        <strong>Coupon applied more than once.</strong>
        <div class="mono mt-16"><?= $flag ?></div>
    </div>
<?php endif; ?>

<div class="split">
    <div class="card">
        <div class="card-head">
            <h2>Charges</h2>
            <span class="badge">Account <?= $userId ?></span>
        </div>
        <div class="card-body">
            <?php if ($fees && mysqli_num_rows($fees) > 0): ?>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr><th>Description</th><th>Amount</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php while ($fee = mysqli_fetch_assoc($fees)): ?>
                                <tr>
                                    <td data-label="Description"><?= $fee['description'] ?></td>
                                    <td data-label="Amount"><?= number_format((float) $fee['amount'], 2) ?></td>
                                    <td data-label="Status"><span class="badge"><?= $fee['status'] ?></span></td>
                                    <td data-label="Action">
                                        <form class="form" method="post" action="">
                                            <input type="hidden" name="fee_id" value="<?= $fee['id'] ?>">
                                            <input type="hidden" name="amount" value="<?= $fee['amount'] ?>">
                                            <div class="field">
                                                <label for="coupon-<?= $fee['id'] ?>">Coupon</label>
                                                <input id="coupon-<?= $fee['id'] ?>" name="coupon" type="text" placeholder="Optional">
                                            </div>
                                            <div class="field">
                                                <label for="method-<?= $fee['id'] ?>">Method</label>
                                                <select id="method-<?= $fee['id'] ?>" name="method">
                                                    <option value="card">Card</option>
                                                    <option value="bank">Bank transfer</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="error">Simulate gateway error</option>
                                                </select>
                                            </div>
                                            <button class="btn btn-primary btn-sm" type="submit">Pay fee</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty">
                    <strong>No charges posted</strong>
                    <span>The finance office will post fees at the start of term.</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <aside class="sidebar">
        <div class="card">
            <div class="card-body">
                <h3>Payment notes</h3>
                <ul class="list small muted">
                    <li>Coupons apply to the posted amount.</li>
                    <li>Card payments clear at the end of the day.</li>
                    <li>Contact the finance office for a receipt.</li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Need a different account?</h3>
                <p class="small muted">The finance desk can move a charge to another student record.</p>
                <a class="btn btn-secondary btn-sm" href="<?= url('contact.php') ?>">Contact finance</a>
            </div>
        </div>
    </aside>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
