<?php

namespace Threes;

require '../vendor/autoload.php';

use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

# Bootstrap
header('Cache-Control: max-age=300');
R::setup('sqlite:data.db');

$date = date('Y-m-d');
$task = R::findOne('task', ' date = ? ', [$date]) ?? R::dispense('task');
$task->date = $date;
if ($_POST) {
    $allowed_keys = ['task1', 'task2', 'task3', 'done1', 'done2', 'done3', 'notes'];
    $_POST = array_intersect_key($_POST, array_flip($allowed_keys));
    foreach (['done1', 'done2', 'done3'] as $key) {
        if (isset($_POST[$key])) {
            $_POST[$key] = 'checked';
        } else {
            $_POST[$key] = '';
        }
    }
    foreach ($_POST as $key => $value) {
        $task[$key] = $value;
    }
    try {
        R::store($task);
    } catch (SQL $e) {
        die($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Tasks</title>
    <script src="https://cdn.jsdelivr.net/npm/htmx.org/dist/htmx.min.js"></script>
    <style>
        input[type=checkbox] {
            cursor: pointer;
        }

        body {
            max-width: 65ch;
        }

        fieldset {
            margin-bottom: 2rem;
        }

        label {
            display: block;
        }

        h2 {
            margin-top: 0;
        }

        textarea {
            width: 100%;
            height: 10em;
            border-color: black;
            border-width: 2px;
        }

        input {
            min-width: 2em;
            height: 2em;
            border-color: black;
            border-width: 2px;
        }

        input[type=text] {
            width: 80%;
        }


    </style>
</head>
<body>
<form method="post" hx-post="/" hx-trigger="change, input delay:5s">
    <h1>Daily Tasks for <?= $task->date ?></h1>

    <fieldset class="tasklist">
        <legend>Tasks</legend>
        <label class="task-item">
            <input type="checkbox" name="done1" <?= $task->done1 ?>>
            <input type="text" name="task1" value="<?= $task->task1 ?>">
        </label>
        <label class="task-item">
            <input type="checkbox" name="done2" <?= $task->done2 ?>>
            <input type="text" name="task2" value="<?= $task->task2 ?>">
        </label>
        <label class="task-item">
            <input type="checkbox" name="done3" <?= $task->done3 ?>>
            <input type="text" name="task3" value="<?= $task->task3 ?>">
        </label>
    </fieldset>
    <fieldset class="meta">
        <legend>Notes</legend>
        <label class="task-item"><strong>Notes</strong>
            <textarea name="notes"><?= $task->notes ?></textarea>
        </label>
    </fieldset>
    <p><input type="submit" id="submit"></p>
</form>
<script>
    // hide submit if htmx is loaded
    if (htmx !== undefined) {
        document.querySelector('#submit').style.display = 'none';
    }
</script>
</body>
</html>
