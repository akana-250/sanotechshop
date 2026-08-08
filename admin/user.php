<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/admin_check.php";
require_once __DIR__ . "/../includes/header.php";

$stmt = $conn->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex">

    <?php require_once __DIR__ . "/../includes/admin_sidebar.php"; ?>

    <div class="p-4 flex-grow-1">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="fw-bold">
                    <i class="bi bi-people-fill"></i>
                    Users Management
                </h2>

                <a href="<?= BASE_URL ?>/admin/user/add_user.php" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill"></i>
                    Add User
                </a>

            </div>

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-dark">

                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th width="170">Action</th>
                                </tr>

                            </thead>

                            <tbody>

                            <?php if(count($users)>0): ?>

                                <?php foreach($users as $user): ?>

                                <tr>

                                    <td><?= $user['id']; ?></td>

                                    <td><?= htmlspecialchars($user['fullname']); ?></td>

                                    <td><?= htmlspecialchars($user['username']); ?></td>

                                    <td><?= htmlspecialchars($user['email']); ?></td>

                                    <td><?= htmlspecialchars($user['phone']); ?></td>

                                    <td>

                                        <?php if($user['role']=="Admin"): ?>

                                            <span class="badge bg-primary">Admin</span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">User</span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <?php if($user['status']=="Approved"): ?>

                                            <span class="badge bg-success">Approved</span>

                                        <?php elseif($user['status']=="Pending"): ?>

                                            <span class="badge bg-warning text-dark">Pending</span>

                                        <?php else: ?>

                                            <span class="badge bg-danger">Blocked</span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <a href="<?= BASE_URL ?>/admin/user/edit_user.php?id=<?= $user['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="<?= BASE_URL ?>/admin/user/delete_user.php?id=<?= $user['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Delete this user?')">

                                            <i class="bi bi-trash"></i>

                                        </a>

                                    </td>

                                </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>

                                    <td colspan="8" class="text-center">

                                        No Users Found

                                    </td>

                                </tr>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>