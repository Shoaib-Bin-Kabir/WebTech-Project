<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    echo '<p style="color: red;">Unauthorized access.</p>';
    exit();
}

if ($_SESSION['user_type'] !== 'Admin') {
    echo '<p style="color: red;">Unauthorized access.</p>';
    exit();
}

include "../Model/DBConnectr.php";

$searchTerm = $_POST['searchTerm'] ?? '';

$db = new DBConnectr();
$connection = $db->openConnection();


if (!empty($searchTerm)) {
   
    $query = "SELECT * FROM history WHERE user_name LIKE ? ORDER BY created_at DESC";
    $searchPattern = '%' . $searchTerm . '%';
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $searchPattern);
    $stmt->execute();
    $history = $stmt->get_result();
} else {
   
    $history = $db->getAllHistory($connection);
}


?>
<table class="history-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Name</th>
            <th>Action</th>
            <th>Change</th>
            <th>Value</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($history && $history->num_rows > 0): ?>
            <?php while ($record = $history->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['id']); ?></td>
                    <td><?php echo htmlspecialchars($record['user_email']); ?></td>
                    <td><?php echo htmlspecialchars($record['user_name'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($record['action_type']); ?></td>
                    <td><?php echo htmlspecialchars($record['target']); ?></td>
                    <td>
                        <?php 
                        if ($record['old_value'] !== NULL && $record['new_value'] !== NULL) {
                            echo 'From ' . htmlspecialchars($record['old_value']) . ' to ' . htmlspecialchars($record['new_value']);
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($record['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #666;">
                    <?php echo !empty($searchTerm) ? 'No results found for "' . htmlspecialchars($searchTerm) . '"' : 'No history records found.'; ?>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php
$db->closeConnection($connection);
?>