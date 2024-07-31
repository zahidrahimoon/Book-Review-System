<?php include 'config/db.php'; ?>

<?php
if (isset($_POST['submit'])) {
    $book_id = $_POST['book_id'];
    $review_text = $conn->real_escape_string($_POST['review_text']);
    $rating = $_POST['rating'];
    
    $sql = "INSERT INTO reviews (book_id, review_text, rating) VALUES ('$book_id', '$review_text', '$rating')";
    if ($conn->query($sql) === TRUE) {
        header("Location: viewBook.php?id=$book_id");
        exit();
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Add a Review</h1>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form action="addReview.php" method="POST" class="mt-4">
        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
        <div class="form-group">
            <label for="review_text">Review</label>
            <textarea name="review_text" id="review_text" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="rating">Rating</label>
            <select name="rating" id="rating" class="form-control" required>
                <option value="">Select a rating</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit Review</button>
    </form>

    <a href="viewBook.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" class="btn btn-secondary mt-3">Back to Book</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
