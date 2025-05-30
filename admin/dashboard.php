<?php
// admin/dashboard.php
require_once "../config/database.php";
require_once "../includes/functions.php";

requireAdminLogin();

$message = "";

// Handle add or edit product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $description = $_POST['description'] ?? '';

    if ($id) {
        // Update product
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $price, $description, $id]);
        $message = "Produk berhasil diperbarui.";
    } else {
        // Insert new product
        $stmt = $pdo->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $description]);
        $message = "Produk berhasil ditambahkan.";
    }
}

// Handle delete product
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$deleteId]);
    $message = "Produk berhasil dihapus.";
}

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Dashboard Admin</h2>
    <a href="logout.php" class="btn btn-danger mb-3">Logout</a>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <h4>Tambah / Edit Produk</h4>
    <form method="POST" action="">
        <input type="hidden" name="id" id="product-id" />
        <div class="mb-3">
            <label for="name" class="form-label">Nama Produk</label>
            <input type="text" name="name" id="product-name" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" name="price" id="product-price" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea name="description" id="product-description" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" onclick="clearForm()">Batal</button>
    </form>

    <h4 class="mt-5">Daftar Produk</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['id']) ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editProduct(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['name'])) ?>', <?= $product['price'] ?>, '<?= htmlspecialchars(addslashes($product['description'])) ?>')">Edit</button>
                    <a href="?delete=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function editProduct(id, name, price, description) {
    document.getElementById('product-id').value = id;
    document.getElementById('product-name').value = name;
    document.getElementById('product-price').value = price;
    document.getElementById('product-description').value = description;
}

function clearForm() {
    document.getElementById('product-id').value = '';
    document.getElementById('product-name').value = '';
    document.getElementById('product-price').value = '';
    document.getElementById('product-description').value = '';
}
</script>
</body>
</html>
