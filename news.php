<?php
session_start();
include './header.php';
include './db_connect.php';

// Debug session role
echo "<!-- Session Role Debug: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set') . " -->";

// Fetch news and events from the database (for initial display)
$query = "SELECT id, news_event_name, news_content, created_at FROM news_events ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    $error_message = "Error fetching news: " . mysqli_error($conn);
}
?>

<head>
    <link rel="stylesheet" href="./CSS/news.css">
</head>

<div class="page" id="news">
    <div class="content">
        <h2>News & Events</h2>
        <p class="intro-text">Stay updated with the latest Zigma Hospital news and events...</p>

        <?php if (isset($_SESSION['delete_success'])): ?>
            <p class="success-message"><?php echo $_SESSION['delete_success']; unset($_SESSION['delete_success']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['delete_error'])): ?>
            <p class="error-message"><?php echo $_SESSION['delete_error']; unset($_SESSION['delete_error']); ?></p>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php elseif (mysqli_num_rows($result) > 0): ?>
            <div class="news-list">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="news-item">
                        <h3 class="news-title"><?php echo htmlspecialchars($row['news_event_name']); ?></h3>
                        <p class="news-description"><?php echo htmlspecialchars($row['news_content']); ?></p>
                        <p class="news-date">Posted on: <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-news">No news or events available yet.</p>
        <?php endif; ?>

        <!-- Show Delete News Button for Admins -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="#" class="delete-news-btn" onclick="showDeleteNewsModal(event)">Delete News & Events</a>
        <?php endif; ?>

        <!-- Delete News & Events Modal -->
        <div id="deleteNewsModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeDeleteNewsModal()">×</span>
                <h2>Delete News & Events</h2>
                <button class="delete-all-btn" onclick="deleteAllNewsItems()">Delete All</button>
                <div id="newsList" class="items-list"></div>
            </div>
        </div>
    </div>
</div>

<?php include './footer.php'; ?>
<?php mysqli_close($conn); ?>

<script>
// Show Delete News Modal
function showDeleteNewsModal(event) {
    event.preventDefault();
    document.getElementById('deleteNewsModal').style.display = 'block';
    fetchNewsItems();
    console.log('Modal opened at ' + new Date().toLocaleString() + ', fetching news items...');
}

// Close Delete News Modal
function closeDeleteNewsModal() {
    document.getElementById('deleteNewsModal').style.display = 'none';
    console.log('Modal closed at ' + new Date().toLocaleString() + '.');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const deleteNewsModal = document.getElementById('deleteNewsModal');
    if (event.target == deleteNewsModal) {
        deleteNewsModal.style.display = 'none';
        console.log('Modal closed by clicking outside at ' + new Date().toLocaleString() + '.');
    }
}

// Fetch News & Events
function fetchNewsItems() {
    console.log('Starting fetch at ' + new Date().toLocaleString() + '...');
    fetch('fetch-news.php', {
        method: 'GET', // Explicitly set method to GET
        credentials: 'same-origin' // Include cookies for session
    })
    .then(response => {
        console.log('Fetch response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log('Fetched data at ' + new Date().toLocaleString() + ':', data);
        const newsList = document.getElementById('newsList');
        newsList.innerHTML = '';
        if (data && Array.isArray(data) && data.length > 0) {
            data.forEach(item => {
                if (item.id && item.news_event_name && item.news_content && item.created_at) {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'item-card';
                    itemDiv.innerHTML = `
                        <h3 class="item-title">${item.news_event_name}</h3>
                        <p class="item-content">${item.news_content}</p>
                        <p class="item-date">Posted on: ${new Date(item.created_at).toLocaleString()}</p>
                        <button class="delete-btn" onclick="deleteNewsItem(${item.id})">Delete</button>
                    `;
                    newsList.appendChild(itemDiv);
                    console.log(`Added item at ${new Date().toLocaleString()}: ${item.news_event_name} with Delete button`);
                } else {
                    console.log('Invalid item data at ' + new Date().toLocaleString() + ':', item);
                }
            });
        } else {
            newsList.innerHTML = '<p class="no-items">No news or events available.</p>';
            console.log('No news items found or invalid data format at ' + new Date().toLocaleString() + '.');
        }
    })
    .catch(error => {
        console.error('Error fetching news at ' + new Date().toLocaleString() + ':', error);
        document.getElementById('newsList').innerHTML = '<p class="error-message">Error loading news. Check console for details.</p>';
    });
}

// Delete News Item
function deleteNewsItem(id) {
    if (confirm('Are you sure you want to delete this news/event?')) {
        fetch('delete-news-process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('News/Event deleted successfully.');
                fetchNewsItems();
                window.location.reload();
            } else {
                alert('Error deleting news/event: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting news at ' + new Date().toLocaleString() + ':', error);
            alert('Error deleting news/event.');
        });
    }
}

// Delete All News Items
function deleteAllNewsItems() {
    if (confirm('Are you sure you want to delete ALL news and events? This action cannot be undone.')) {
        fetch('delete-all-news-process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: '',
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('All News/Events deleted successfully.');
                fetchNewsItems();
                window.location.reload();
            } else {
                alert('Error deleting all news/events: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting all news at ' + new Date().toLocaleString() + ':', error);
            alert('Error deleting all news/events.');
        });
    }
}
</script>