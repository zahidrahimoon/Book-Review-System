<?php include 'config/db.php'; ?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM reviews WHERE review_id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Review deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
