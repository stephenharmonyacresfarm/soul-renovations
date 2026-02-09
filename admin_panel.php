<?php
// admin_panel.php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

require_once 'db_config.php';
$message = '';

// Handle photo upload
if (isset($_POST['add_photo'])) {
    $alt_text = $_POST['alt_text'] ?? '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $file_extension;
        $filepath = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $filepath)) {
            $stmt = $pdo->prepare('INSERT INTO photos (filename, alt_text) VALUES (:filename, :alt_text)');
            $stmt->execute([':filename' => $filename, ':alt_text' => $alt_text]);
            $message = 'Photo uploaded successfully!';
        } else {
            $message = 'Error uploading photo.';
        }
    }
}

// Handle photo deletion
if (isset($_POST['delete_photo'])) {
    $photo_id = $_POST['photo_id'];

    $stmt = $pdo->prepare('SELECT filename FROM photos WHERE id = :id');
    $stmt->execute([':id' => $photo_id]);
    $photo = $stmt->fetch();

    if ($photo) {
        @unlink('uploads/' . $photo['filename']);
        $stmt = $pdo->prepare('DELETE FROM photos WHERE id = :id');
        $stmt->execute([':id' => $photo_id]);
        $message = 'Photo deleted successfully!';
    }
}

// Handle review addition
if (isset($_POST['add_review'])) {
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $rating = $_POST['rating'] ?? 5;
    $review_text = $_POST['review_text'] ?? '';

    $stmt = $pdo->prepare('INSERT INTO reviews (name, location, rating, review_text) VALUES (:name, :location, :rating, :review_text)');
    $stmt->execute([':name' => $name, ':location' => $location, ':rating' => $rating, ':review_text' => $review_text]);
    $message = 'Review added successfully!';
}

// Handle review deletion
if (isset($_POST['delete_review'])) {
    $review_id = $_POST['review_id'];
    $stmt = $pdo->prepare('DELETE FROM reviews WHERE id = :id');
    $stmt->execute([':id' => $review_id]);
    $message = 'Review deleted successfully!';
}

// Get all photos
$photos = $pdo->query('SELECT * FROM photos ORDER BY created_at DESC')->fetchAll();

// Get all reviews
$reviews = $pdo->query('SELECT * FROM reviews ORDER BY created_at DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Soul Renovations</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f4f4;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.5rem;
        }

        .logout-btn {
            background: #e67e22;
            color: white;
            padding: 0.5rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #d35400;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .message {
            background: #27ae60;
            color: white;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
        }

        .section {
            background: white;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .section h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e67e22;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button, .btn {
            background: #e67e22;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }

        button:hover, .btn:hover {
            background: #d35400;
        }

        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .photo-item {
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }

        .photo-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .photo-info {
            padding: 1rem;
            background: #f9f9f9;
        }

        .photo-info p {
            margin-bottom: 0.5rem;
            color: #555;
            font-size: 0.9rem;
        }

        .delete-btn {
            background: #e74c3c;
            width: 100%;
            margin-top: 0.5rem;
        }

        .delete-btn:hover {
            background: #c0392b;
        }

        .review-item {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: #f9f9f9;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .review-stars {
            color: #f39c12;
            font-size: 1.2rem;
        }

        .review-text {
            margin: 1rem 0;
            line-height: 1.6;
        }

        .review-author {
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Panel - Soul Renovations</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Photo Management Section -->
        <div class="section">
            <h2>Add New Photo</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="photo">Select Photo</label>
                    <input type="file" id="photo" name="photo" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="alt_text">Description (Alt Text)</label>
                    <input type="text" id="alt_text" name="alt_text" placeholder="e.g., Kitchen renovation project">
                </div>

                <button type="submit" name="add_photo">Upload Photo</button>
            </form>

            <h2 style="margin-top: 2rem;">Existing Photos</h2>
            <div class="photo-grid">
                <?php foreach ($photos as $photo): ?>
                    <div class="photo-item">
                        <img src="uploads/<?php echo htmlspecialchars($photo['filename']); ?>" alt="<?php echo htmlspecialchars($photo['alt_text']); ?>">
                        <div class="photo-info">
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($photo['alt_text']); ?></p>
                            <p><strong>Added:</strong> <?php echo date('M d, Y', strtotime($photo['created_at'])); ?></p>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this photo?');">
                                <input type="hidden" name="photo_id" value="<?php echo $photo['id']; ?>">
                                <button type="submit" name="delete_photo" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Review Management Section -->
        <div class="section">
            <h2>Add New Review</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="name">Customer Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" placeholder="e.g., Lancaster, PA" required>
                </div>

                <div class="form-group">
                    <label for="rating">Rating</label>
                    <select id="rating" name="rating" required>
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="review_text">Review Text</label>
                    <textarea id="review_text" name="review_text" required></textarea>
                </div>

                <button type="submit" name="add_review">Add Review</button>
            </form>

            <h2 style="margin-top: 2rem;">Existing Reviews</h2>
            <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <div class="review-header">
                        <div>
                            <div class="review-stars">
                                <?php echo str_repeat('★', $review['rating']); ?>
                            </div>
                            <div class="review-author"><?php echo htmlspecialchars($review['name']); ?></div>
                            <div><?php echo htmlspecialchars($review['location']); ?></div>
                        </div>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                            <button type="submit" name="delete_review" class="delete-btn">Delete</button>
                        </form>
                    </div>
                    <div class="review-text"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></div>
                    <small>Added: <?php echo date('M d, Y', strtotime($review['created_at'])); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
