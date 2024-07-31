<?php include 'config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Book</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Check if the book exists before attempting to delete it
        $sql_check = "SELECT * FROM books WHERE book_id = $id";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            $book = $result_check->fetch_assoc();
            $cover_image = $book['cover_image'];

            // Delete related reviews first
            $sql_delete_reviews = "DELETE FROM reviews WHERE book_id = $id";
            if ($conn->query($sql_delete_reviews) === TRUE) {
                // Proceed with deleting the book
                $sql_delete_book = "DELETE FROM books WHERE book_id = $id";
                if ($conn->query($sql_delete_book) === TRUE) {
                    // Delete the cover image file if it exists
                    if ($cover_image && file_exists($cover_image)) {
                        unlink($cover_image);
                    }
                    echo "<div class='alert alert-success' role='alert'>Book and related reviews deleted successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Failed to delete book: " . $conn->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger' role='alert'>Failed to delete reviews: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning' role='alert'>Book not found.</div>";
        }
    } else {
        echo "<div class='alert alert-warning' role='alert'>No book ID provided.</div>";
    }
    ?>
    <a href="index.php" class="btn btn-primary mt-3">Go Back</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
