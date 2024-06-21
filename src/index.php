<?php

namespace Threes;

require '../vendor/autoload.php';

use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

# Bootstrap
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
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>Three Tasks for <?= $date ?></title>
    <script src="htmx.min.js?1.9.2"></script>
    <script src="idiomorph-ext.min.js"></script>
    <style>
        input[type=checkbox] {
            cursor: pointer;
        }

        html {
            background: #9999cc;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            min-height: 98vmin;
            padding: 1rem;
            max-width: 65ch;
            background: white;
            font: 16px/1.5 "Inter", sans-serif;
            box-shadow: rebeccapurple 0 0 20px;
        }

        fieldset {
            margin-bottom: 2rem;
            border: 1px dashed purple;
            font-weight: bold;
            color: purple;
        }

        footer {
            margin: 0 auto;
            text-align: center;
            font-size: .75rem;
            opacity: 75%;
            position: fixed;
            left: 0;
            bottom: 0.5rem;
            max-width: 65ch;
            padding: 0 10px;
        }

        label {
            display: block;
            margin-bottom: 1rem;
        }

        label input {
            vertical-align: middle;
        }

        textarea {
            width: 100%;
            height: 15rem;
            border-color: black;
            border-width: 0 0 0 0;
        }

        input {
            height: 2em;
            min-width: 2em;
            border-color: black;
            border-width: 1px;
        }

        input, textarea {
            font: 0.875em/1.2em "JetBrains Mono", "Roboto Mono", monospace;
        }

        input[type=text] {
            width: 80%;
            border-width: 0 0 1px 0;
        }

        h1 {
            margin-top: 0;
            font-weight: normal;
        }


    </style>
</head>
<body>
<form method="post" hx-post="/" hx-trigger="input delay:1s" hx-swap="morph:{ignoreActiveValue:true}">
    <h1>Three Tasks for <?= $task->date ?></h1>

    <fieldset class="tasklist">
        <legend>Tasks</legend>
        <label class="task-item">
            <input tabindex=90 type="checkbox" name="done1" id="done1" <?= $task->done1 ?>>
            <input tabindex=20 type="text" name="task1" id="task1" value="<?= $task->task1 ?>">
        </label>
        <label class="task-item">
            <input tabindex=91 type="checkbox" name="done2" id="done2" <?= $task->done2 ?>>
            <input tabindex=21 type="text" name="task2" id="task2" value="<?= $task->task2 ?>">
        </label>
        <label class="task-item">
            <input tabindex=92 type="checkbox" name="done3" id="done3" <?= $task->done3 ?>>
            <input tabindex=22 type="text" name="task3" id="task3" value="<?= $task->task3 ?>">
        </label>
    </fieldset>
    <fieldset class="meta">
        <legend>Notes</legend>
        <label class="task-item">
            <textarea tabindex=10 name="notes" id="notes"><?= $task->notes ?></textarea>
        </label>
    </fieldset>
</form>
<footer>Powered by <a href="https://github.com/svandragt/daily-tasks">Three Tasks</a></footer>
</body>
</html>
