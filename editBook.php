<?php include 'config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Edit Book</h1>
    <?php
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Ensure ID is an integer

        // Fetch book details
        $sql = "SELECT books.*, authors.name AS author_name 
                FROM books 
                LEFT JOIN authors ON books.author_id = authors.author_id 
                WHERE book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
    }

    if (isset($_POST['submit'])) {
        $id = intval($_POST['id']);
        $title = $conn->real_escape_string($_POST['title']);
        $author_name = $conn->real_escape_string($_POST['author']);
        $cover_image = $book['cover_image'];

        // Handle file upload
        if (!empty($_FILES['cover_image']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["cover_image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Check if the file is a valid image
            $check = getimagesize($_FILES["cover_image"]["tmp_name"]);
            if ($check === false) {
                echo "File is not an image.";
                exit;
            }

            // Check file size (limit to 5MB)
            if ($_FILES["cover_image"]["size"] > 5000000) {
                echo "Sorry, your file is too large.";
                exit;
            }

            // Allow certain file formats
            $allowed_formats = array("jpg", "jpeg", "png", "gif" ,"avif");
            if (!in_array($imageFileType, $allowed_formats)) {
                echo "Sorry, only JPG, JPEG, PNG , AVIF & GIF files are allowed.";
                exit;
            }

            if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                $cover_image = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        }

        // Check if author exists
        $sql = "SELECT author_id FROM authors WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $author_name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $author = $result->fetch_assoc();
            $author_id = $author['author_id'];
        } else {
            // Insert new author
            $sql = "INSERT INTO authors (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $author_name);
            if ($stmt->execute()) {
                $author_id = $conn->insert_id;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                exit;
            }
        }

        // Update book
        $sql = "UPDATE books SET title=?, author_id=?, cover_image=? WHERE book_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisi", $title, $author_id, $cover_image, $id);
        if ($stmt->execute()) {
            header("Location: viewBook.php?id=$id");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>
    <form action="editBook.php" method="POST" enctype="multipart/form-data" class="mt-4">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($book['book_id']); ?>">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" name="author" id="author" class="form-control" value="<?php echo htmlspecialchars($book['author_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="cover_image">Cover Image</label>
            <input type="file" name="cover_image" id="cover_image" class="form-control-file">
        </div>
        <?php if ($book['cover_image']): ?>
            <div class="form-group">
                <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Book Cover" class="img-thumbnail" width="100">
            </div>
        <?php endif; ?>
        <button type="submit" name="submit" class="btn btn-primary">Update Book</button>
        <div class="d-flex justify-content-end">
        <a href="index.php" class="btn btn-danger mt-3">Back</a>
    </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
