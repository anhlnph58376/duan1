<?php
// Seed demo admin and HDV users

// Bootstrap minimal app context
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Paths
define('PATH_ROOT', __DIR__ . '/..');
require_once PATH_ROOT . '/configs/env.php';
require_once PATH_ROOT . '/models/BaseModel.php';
require_once PATH_ROOT . '/models/User.php';

function ensureDir($dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

try {
    $userModel = new User();

    // Admin user
    $admin = $userModel->findByUsername('admin');
    if (!$admin) {
        $userModel->create([
            'username' => 'admin',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'role_id' => 1,
            'is_active' => 1,
        ]);
        echo "✓ Created admin (username: admin / password: admin123)\n";
    } else {
        echo "→ Admin already exists.\n";
    }

    // HDV user
    $hdv = $userModel->findByUsername('hdv');
    if (!$hdv) {
        // Try to link to first tour_guide if exists
        $pdo = $userModel->getPdo();
        $guideId = null;
        $stmt = $pdo->query("SELECT id FROM tour_guides ORDER BY id ASC LIMIT 1");
        $row = $stmt->fetch();
        if ($row) {
            $guideId = (int)$row['id'];
        }
        $userModel->create([
            'username' => 'hdv',
            'password_hash' => password_hash('hdv123', PASSWORD_DEFAULT),
            'role_id' => 2,
            'tour_guide_id' => $guideId,
            'is_active' => 1,
        ]);
        echo "✓ Created HDV (username: hdv / password: hdv123)\n";
        if ($guideId) {
            echo "  → Linked to tour_guide ID: $guideId\n";
        }
    } else {
        echo "→ HDV already exists.\n";
    }

    // Prepare sample avatar directory
    $avatarDir = PATH_ROOT . '/uploads/avatars';
    ensureDir($avatarDir);

    echo "\n✓ Seeding completed successfully!\n";
    echo "\nLogin credentials:\n";
    echo "  Admin: admin / admin123\n";
    echo "  HDV:   hdv / hdv123\n";
} catch (Throwable $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
