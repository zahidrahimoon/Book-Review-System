<?php include 'config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Search Results</h1>
        <a href="index.php" class="btn btn-primary mb-4">Go Back</a>
        <ul class="list-group">
            <?php
            if (isset($_GET['query'])) {
                $query = $conn->real_escape_string($_GET['query']);
                $sql = "SELECT books.*, authors.name AS author_name 
                        FROM books 
                        LEFT JOIN authors ON books.author_id = authors.author_id 
                        WHERE books.title LIKE '%$query%' OR authors.name LIKE '%$query%'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<li class='list-group-item'>
                                <a href='viewBook.php?id=" . $row["book_id"] . "'>" . $row["title"] . "</a> 
                                by " . $row["author_name"] . "
                              </li>";
                    }
                } else {
                    echo "<li class='list-group-item'>No books found.</li>";
                }
            }
            ?>
        </ul>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
