<?php include './header.php'; ?>
<?php include './db_connect.php'; ?>

<div class="page" id="reviews">
    <div class="content">
        <h2>Submit a Review</h2>
        <form class="review-form" action="reviews.php" method="post">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="review_text">Your Review:</label>
            <textarea id="review_text" name="review_text" rows="5" required></textarea>
            <button type="submit" name="submit_review">Submit Review</button>
        </form>

        <?php
        if (isset($_POST['submit_review'])) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

            $query = "INSERT INTO reviews (name, review_text) VALUES ('$name', '$review_text')";
            if (mysqli_query($conn, $query)) {
                echo "<p style='color: green;'>Review submitted successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error submitting review: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>
    </div>
</div>

<?php include './footer.php'; ?>
<?php mysqli_close($conn); ?>