<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Review System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 class="mt-2 text-center py-2">Book Review System</h1>
    <div class="container">
    <form id="searchForm" action="search.php" method="GET" class="mb-4" novalidate onsubmit="return validateForm()">
    <div class="input-group mb-3">
        <input type="text" name="query" id="query" class="form-control form-control-md" placeholder="Search by title or author" aria-label="Search">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>

<script>
function validateForm() {
    var query = document.getElementById('query').value;
    if (query.trim() === "") {
        alert("Please Enter the data First.");
        return false;
    }
    return true;
}
</script>


        <h2 class="text-center">Book List</h2>
        <a href="addBook.php" class="btn btn-primary mb-3">Add New Book</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Reviews</th>    
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                $sql = "SELECT books.*, authors.name AS author_name, COUNT(reviews.review_id) AS review_count 
                    FROM books 
                    LEFT JOIN authors ON books.author_id = authors.author_id 
                    LEFT JOIN reviews ON books.book_id = reviews.book_id 
                    GROUP BY books.book_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $counter++ . "</td>
                            <td><a href='viewBook.php?id=" . $row["book_id"] . "'>" . $row["title"] . "</a></td>
                            <td>" . $row["author_name"] . "</td>
                            <td>" . $row["review_count"] . "</td>    
                            <td>
                                <a href='editBook.php?id=" . $row["book_id"] . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='deleteBook.php?id=" . $row["book_id"] . "' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                          </tr>";       
                    }
                } else {
                    echo "<tr><td colspan='6'>No books found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>