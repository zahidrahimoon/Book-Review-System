<?php include 'config/db.php'; ?>

<?php
// Initialize variables for error and success messages
$error_message = '';
$success_message = '';

// Check if form was submitted
if (isset($_POST['submit'])) {
    $review_id = intval($_POST['review_id']);
    $book_id = intval($_POST['book_id']);
    $review_text = $conn->real_escape_string($_POST['review_text']);
    $rating = intval($_POST['rating']);

    // Update review
    $sql = "UPDATE reviews SET review_text = ?, rating = ? WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $review_text, $rating, $review_id);
    
    if ($stmt->execute()) {
        $success_message = "Review updated successfully.";
        header("Location: viewBook.php?id=$book_id");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }
}

// Initialize $review variable
$review = null;

// Retrieve review details
if (isset($_GET['id'])) {
    $review_id = intval($_GET['id']);

    $sql = "SELECT * FROM reviews WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if review exists
    if ($result->num_rows > 0) {
        $review = $result->fetch_assoc();
    } else {
        $error_message = "Review not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Edit Review</h1>

    <?php if ($success_message): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if ($review): ?>
        <form action="editReview.php" method="POST" class="mt-4">
            <input type="hidden" name="review_id" value="<?php echo htmlspecialchars($review['review_id']); ?>">
            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($review['book_id']); ?>">
            <div class="form-group">
                <label for="review_text">Review</label>
                <textarea name="review_text" id="review_text" class="form-control" rows="5" required><?php echo htmlspecialchars($review['review_text']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <select name="rating" id="rating" class="form-control" required>
                    <option value="">Select a rating</option>
                    <option value="1" <?php echo ($review['rating'] == 1) ? 'selected' : ''; ?>>1 - Poor</option>
                    <option value="2" <?php echo ($review['rating'] == 2) ? 'selected' : ''; ?>>2 - Fair</option>
                    <option value="3" <?php echo ($review['rating'] == 3) ? 'selected' : ''; ?>>3 - Good</option>
                    <option value="4" <?php echo ($review['rating'] == 4) ? 'selected' : ''; ?>>4 - Very Good</option>
                    <option value="5" <?php echo ($review['rating'] == 5) ? 'selected' : ''; ?>>5 - Excellent</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update Review</button>
        </form>
    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            Review not found.
        </div>
    <?php endif; ?>

    <a href="viewBook.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" class="btn btn-secondary mt-3">Back to Book</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
