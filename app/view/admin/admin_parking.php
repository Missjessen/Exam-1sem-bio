<?php
require_once dirname(__DIR__, 3) . '/init.php';




class ShowingsManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Hent alle visninger med tilhørende filmtitel
    public function getAllShowings() {
        $sql = "SELECT showings.*, movies.title AS movie_title 
                FROM showings 
                JOIN movies ON showings.movie_id = movies.id";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tilføj en visning
    public function addShowing($data) {
        $sql = "INSERT INTO showings (movie_id, screen, show_date, show_time, total_spots, available_spots, repeat_pattern, repeat_until) 
                VALUES (:movie_id, :screen, :show_date, :show_time, :total_spots, :available_spots, :repeat_pattern, :repeat_until)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // Opdater en visning
    public function updateShowing($id, $data) {
        $sql = "UPDATE showings 
                SET movie_id = :movie_id, screen = :screen, show_date = :show_date, show_time = :show_time, 
                    total_spots = :total_spots, available_spots = :available_spots, 
                    repeat_pattern = :repeat_pattern, repeat_until = :repeat_until 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    // Slet en visning
    public function deleteShowing($id) {
        $sql = "DELETE FROM showings WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}

// Instansier ShowingsManager
$showingsManager = new ShowingsManager();

// Håndter POST-anmodninger (tilføj, rediger, slet)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'add') {
            $showingsManager->addShowing([
                'movie_id' => $_POST['movie_id'],
                'screen' => $_POST['screen'],
                'show_date' => $_POST['show_date'],
                'show_time' => $_POST['show_time'],
                'total_spots' => $_POST['total_spots'],
                'available_spots' => $_POST['available_spots'],
                'repeat_pattern' => $_POST['repeat_pattern'],
                'repeat_until' => $_POST['repeat_until'],
            ]);
        } elseif ($action === 'edit') {
            $showingsManager->updateShowing($_POST['id'], [
                'movie_id' => $_POST['movie_id'],
                'screen' => $_POST['screen'],
                'show_date' => $_POST['show_date'],
                'show_time' => $_POST['show_time'],
                'total_spots' => $_POST['total_spots'],
                'available_spots' => $_POST['available_spots'],
                'repeat_pattern' => $_POST['repeat_pattern'],
                'repeat_until' => $_POST['repeat_until'],
            ]);
        } elseif ($action === 'delete') {
            $showingsManager->deleteShowing($_POST['id']);
        }
        header("Location: test_showings.php");
        exit;
    }
}

// Hent alle visninger
$showings = $showingsManager->getAllShowings();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showings Manager</title>
</head>
<body>
    <h1>Showings Manager</h1>

    <!-- Liste over visninger -->
    <h2>All Showings</h2>
    <?php if (!empty($showings)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Movie Title</th>
                <th>Screen</th>
                <th>Date</th>
                <th>Time</th>
                <th>Total Spots</th>
                <th>Available Spots</th>
                <th>Repeat Pattern</th>
                <th>Repeat Until</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($showings as $showing): ?>
                <tr>
                    <td><?= htmlspecialchars($showing['id']) ?></td>
                    <td><?= htmlspecialchars($showing['movie_title']) ?></td>
                    <td><?= htmlspecialchars($showing['screen']) ?></td>
                    <td><?= htmlspecialchars($showing['show_date']) ?></td>
                    <td><?= htmlspecialchars($showing['show_time']) ?></td>
                    <td><?= htmlspecialchars($showing['total_spots']) ?></td>
                    <td><?= htmlspecialchars($showing['available_spots']) ?></td>
                    <td><?= htmlspecialchars($showing['repeat_pattern']) ?></td>
                    <td><?= htmlspecialchars($showing['repeat_until']) ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $showing['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" onclick="return confirm('Delete this showing?')">Delete</button>
                        </form>
                        <button onclick="editShowing(<?= htmlspecialchars(json_encode($showing)) ?>)">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No showings available.</p>
    <?php endif; ?>

    <!-- Tilføj en visning -->
    <h2>Add Showing</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <label>Movie ID: <input type="text" name="movie_id" required></label><br>
        <label>Screen:
            <select name="screen" required>
                <option value="small">Small</option>
                <option value="large">Large</option>
            </select>
        </label><br>
        <label>Date: <input type="date" name="show_date" required></label><br>
        <label>Time: <input type="time" name="show_time" required></label><br>
        <label>Total Spots: <input type="number" name="total_spots" required></label><br>
        <label>Available Spots: <input type="number" name="available_spots" required></label><br>
        <label>Repeat Pattern:
            <select name="repeat_pattern">
                <option value="none">None</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
            </select>
        </label><br>
        <label>Repeat Until: <input type="date" name="repeat_until"></label><br>
        <button type="submit">Add Showing</button>
    </form>

    <!-- Rediger en visning -->
    <h2>Edit Showing</h2>
    <form method="POST" id="editForm" style="display: none;">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" id="editId">
        <label>Movie ID: <input type="text" name="movie_id" id="editMovieId" required></label><br>
        <label>Screen:
            <select name="screen" id="editScreen" required>
                <option value="small">Small</option>
                <option value="large">Large</option>
            </select>
        </label><br>
        <label>Date: <input type="date" name="show_date" id="editShowDate" required></label><br>
        <label>Time: <input type="time" name="show_time" id="editShowTime" required></label><br>
        <label>Total Spots: <input type="number" name="total_spots" id="editTotalSpots" required></label><br>
        <label>Available Spots: <input type="number" name="available_spots" id="editAvailableSpots" required></label><br>
        <label>Repeat Pattern:
            <select name="repeat_pattern" id="editRepeatPattern">
                <option value="none">None</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
            </select>
        </label><br>
        <label>Repeat Until: <input type="date" name="repeat_until" id="editRepeatUntil"></label><br>
        <button type="submit">Update Showing</button>
    </form>

    <script>
        function editShowing(showing) {
            document.getElementById('editId').value = showing.id;
            document.getElementById('editMovieId').value = showing.movie_id;
            document.getElementById('editScreen').value = showing.screen;
            document.getElementById('editShowDate').value = showing.show_date;
            document.getElementById('editShowTime').value = showing.show_time;
            document.getElementById('editTotalSpots').value = showing.total_spots;
            document.getElementById('editAvailableSpots').value = showing.available_spots;
            document.getElementById('editRepeatPattern').value = showing.repeat_pattern;
            document.getElementById('editRepeatUntil').value = showing.repeat_until;
            document.getElementById('editForm').style.display = 'block';
        }
    </script>
</body>
</html>
