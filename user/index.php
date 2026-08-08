<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/config.php";
require_once "../includes/admin_check.php";
require_once "../includes/header.php";
?>

<div class="d-flex">

    <?php require_once "../includes/admin_sidebar.php"; ?>

    <div class="p-4 flex-grow-1">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Users Management</h2>

                <a href="add_user.php" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Add User
                </a>
            </div>

            <?php
            $stmt = $conn->query("SELECT * FROM users ORDER BY id DESC");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (!empty($users)): ?>

                    <?php foreach ($users as $user): ?>

                        <tr>
                            <td><?= $user['id']; ?></td>
                            <td><?= htmlspecialchars($user['fullname']); ?></td>
                            <td><?= htmlspecialchars($user['username']); ?></td>
                            <td><?= htmlspecialchars($user['email']); ?></td>
                            <td><?= htmlspecialchars($user['phone']); ?></td>
                            <td><?= htmlspecialchars($user['role']); ?></td>
                            <td><?= htmlspecialchars($user['status']); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_user.php?id=<?= $user['id']; ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this user?')">Delete</a>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="text-center">No Users Found</td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>

        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>