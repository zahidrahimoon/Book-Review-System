<?php include 'config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Reviews</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <?php
    if (isset($_GET['id'])) {
        $book_id = intval($_GET['id']); // Ensure ID is an integer

        // Fetch book details
        $sql = "SELECT books.title, books.cover_image, authors.name AS author_name 
                FROM books 
                LEFT JOIN authors ON books.author_id = authors.author_id 
                WHERE book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
    ?>

    <div class="row">
        <div class="col-md-4">
            <?php if ($book['cover_image']) { ?>
                <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Book cover" class="img-fluid">
            <?php } ?>
        </div>
        <div class="col-md-8">
            <h1><?php echo htmlspecialchars($book['title']); ?></h1>
            <p class="lead">Author: <?php echo htmlspecialchars($book['author_name']); ?></p>
        </div>
    </div>

    <h2 class="mt-5">Reviews</h2>
    <div class="list-group">
        <?php
        // Fetch reviews
        $sql = "SELECT * FROM reviews WHERE book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            echo "<div class='list-group-item'>";
            echo "<p><strong>Review:</strong> " . htmlspecialchars($row['review_text']) . "</p>";
            echo "<p><strong>Rating:</strong> " . str_repeat('★', intval($row['rating'])) . "</p>";
            echo "<a href='editReview.php?id=" . htmlspecialchars($row['review_id']) . "&book_id=" . htmlspecialchars($book_id) . "' class='btn btn-warning btn-sm'>Edit</a>";
            echo "<a href='deleteReview.php?id=" . htmlspecialchars($row['review_id']) . "&book_id=" . htmlspecialchars($book_id) . "' class='btn btn-danger btn-sm'>Delete</a>";
            echo "</div>";
        }
        ?>
    </div>

    <a href="addReview.php?id=<?php echo htmlspecialchars($book_id); ?>" class="btn btn-primary mt-3">Add Review</a>
    <a href="viewBook.php?id=<?php echo htmlspecialchars($book_id); ?>" class="btn btn-secondary mt-3">Back to Book</a>
    <?php } else { ?>
        <div class="alert alert-danger" role="alert">
            No book selected.
        </div>
    <?php } ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
