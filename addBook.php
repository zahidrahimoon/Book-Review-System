<?php include 'config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="addBook">Add Book</h1>
    <form action="addBook.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Book Title" required><br>
        <input type="text" name="author" placeholder="Author Name" required><br>
        <input type="file" name="cover_image"><br>
        <button type="submit" name="submit">Add Book</button>
        <a href="index.php" class="back" style="color: white; background-color:red; padding:9px 22px; border-radius:5px; text-decoration:none;">Back</a>
    </form>
    <?php
    if (isset($_POST['submit'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $author_name = $conn->real_escape_string($_POST['author']);
        $cover_image = '';

        // Handle file upload
        if (!empty($_FILES['cover_image']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["cover_image"]["name"]);
            if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                $cover_image = $target_file;
            }
        }

        // Check if author exists
        $sql = "SELECT author_id FROM authors WHERE name = '$author_name'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $author = $result->fetch_assoc();
            $author_id = $author['author_id'];
        } else {
            // Insert new author
            $sql = "INSERT INTO authors (name) VALUES ('$author_name')";
            if ($conn->query($sql) === TRUE) {
                $author_id = $conn->insert_id;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Insert book
        $sql = "INSERT INTO books (title, author_id, cover_image) VALUES ('$title', '$author_id', '$cover_image')";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>
</body>
</html>
