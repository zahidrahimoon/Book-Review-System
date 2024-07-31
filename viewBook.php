<?php include 'config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Book</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT books.*, authors.name AS author_name FROM books LEFT JOIN authors ON books.author_id = authors.author_id WHERE book_id = $id";
        $result = $conn->query($sql);
        $book = $result->fetch_assoc();
    ?>

    <div class="row">
        <div class="col-md-4">
            <?php if ($book['cover_image']) { ?>
                <img src="<?php echo $book['cover_image']; ?>" alt="Book cover" class="img-fluid">
            <?php } ?>
        </div>
        <div class="col-md-8">
            <h1><?php echo $book['title']; ?></h1>
            <p class="lead">Author: <?php echo $book['author_name']; ?></p>
        </div>
    </div>

    <h2 class="mt-5">Reviews</h2>
    <div class="list-group">
        <?php
        $sql = "SELECT * FROM reviews WHERE book_id = $id";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<div class='list-group-item'>";
            echo "<p>Review: " . $row['review_text'] . "</p>";
            echo "<p>Rating: " . str_repeat('★', $row['rating']) . "</p>";
            echo "</div>";
        }
        ?>
    </div>

    <a href="addReview.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" class="btn btn-primary mt-3">Add Review</a>
    <a href="viewReviews.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" class="btn btn-warning mt-3">View Review</a>
    <a href="index.php" class="btn btn-danger mt-3" >Back</a>
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
