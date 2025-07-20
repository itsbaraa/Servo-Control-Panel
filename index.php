<?php require_once 'logic.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servo Motors Control</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Servo Motors Control Panel</h1>

    <?php if ($feedback_message): ?>
        <div class="feedback"><?php echo $feedback_message; ?></div>
    <?php endif; ?>

    <form action="index.php" method="post">
        <div class="sliders-container">
            <?php for ($i = 1; $i <= 4; $i++):
                $angle_var = "servo{$i}_angle"; // e.g., $servo1_angle
            ?>
            <div class="slider-group">
                <label for="servo<?php echo $i; ?>">Servo <?php echo $i; ?> Angle:
                    <span id="servo<?php echo $i; ?>_val" class="slider-value"><?php echo $$angle_var; ?></span>°
                </label>
                <input type="range" id="servo<?php echo $i; ?>" name="servo<?php echo $i; ?>" min="0" max="180"
                       value="<?php echo $$angle_var; ?>"
                       oninput="updateSliderValue('servo<?php echo $i; ?>')">
            </div>
            <?php endfor; ?>
        </div>

        <div class="button-group">
            <button type="submit" name="reset">Reset to 90°</button>
            <button type="submit" name="save_position">Save Position</button>
            <button type="submit" name="submit_to_esp">Submit to ESP</button>
        </div>
    </form>

    <h2>Saved Positions</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Servo 1</th>
                <th>Servo 2</th>
                <th>Servo 3</th>
                <th>Servo 4</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($saved_positions)): ?>
                <?php foreach ($saved_positions as $pos): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pos['id']); ?></td>
                        <td><?php echo htmlspecialchars($pos['servo1']); ?>°</td>
                        <td><?php echo htmlspecialchars($pos['servo2']); ?>°</td>
                        <td><?php echo htmlspecialchars($pos['servo3']); ?>°</td>
                        <td><?php echo htmlspecialchars($pos['servo4']); ?>°</td>
                        <td class="action-cell">
                            <a href="index.php?load_id=<?php echo $pos['id']; ?>" class="btn load">Load</a>
                            <a href="index.php?remove_id=<?php echo $pos['id']; ?>" class="btn remove">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No saved positions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="script.js"></script>

</body>
</html>