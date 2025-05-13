<?php
// Konfigurasi API Key
$accessKey = "qd8J9RL4uitc7uCtW4E1-PnlVaAcRu08YYJAjkVOvl0"; // Ganti dengan milikmu

// Fungsi untuk ambil gambar dari Unsplash API
function fetchImages($keyword, $accessKey) {
    $url = "https://api.unsplash.com/search/photos?query=" . urlencode($keyword) . "&client_id=" . $accessKey;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return [];
    }

    $data = json_decode($response, true);
    return $data['results'] ?? [];
}

// Ambil data jika ada pencarian
$images = [];
$keyword = '';
if (isset($_GET['query'])) {
    $keyword = htmlspecialchars(trim($_GET['query']));
    if (!empty($keyword)) {
        $images = fetchImages($keyword, $accessKey);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Galeri Gambar Unsplash</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { padding: 2rem; }
        .image-card { margin-bottom: 20px; }
        img { border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Pencarian Gambar dengan Unsplash API</h1>
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Contoh: nature, car, sunset..." required value="<?= $keyword ?>">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>

        <div class="row">
            <?php if (!empty($images)): ?>
                <?php foreach ($images as $image): ?>
                    <div class="col-md-3 image-card">
                        <img src="<?= htmlspecialchars($image['urls']['small']) ?>" alt="<?= htmlspecialchars($image['alt_description'] ?? 'image') ?>" class="img-fluid">
                    </div>
                <?php endforeach; ?>
            <?php elseif ($keyword): ?>
                <p>Tidak ada hasil ditemukan untuk <strong><?= $keyword ?></strong>.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
