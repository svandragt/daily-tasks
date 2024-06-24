<?php

namespace Threes;

require '../vendor/autoload.php';

use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

# Bootstrap
R::setup('sqlite:data.db');

$date = date('Y-m-d');
$list = R::findOne('task-list', ' date = ? ', [$date]) ?? R::dispense('task-list');
$list->date = $date;
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
        $list[$key] = $value;
    }
    try {
        R::store($list);
    } catch (SQL $e) {
        die($e->getMessage());
    }
}

function is_autofocus($task)
{
    return empty($task) && ! $_POST ? 'autofocus="autofocus"' : '';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>Three Things for <?= $date ?></title>
    <script src="htmx.min.js?1.9.2"></script>
    <script src="idiomorph-ext.min.js"></script>
    <style>
        input[type=checkbox] {
            cursor: pointer;
        }

        html {
            background: rgba(153, 153, 204, 0.25);
            margin: 0;
            padding: 0;
        }

        body {
            margin: .5rem;
            padding: 1rem;
            background: white;
            font: 16px/1.5 "Inter", sans-serif;
            box-shadow: rgba(102, 51, 153, 0.1) 1.95px 1.95px 2.6px;
            border-radius: 0.25rem;
        }

        fieldset {
            margin-bottom: 2rem;
            border: 1px dashed rgba(128, 0, 128, 0.25);
            border-radius: 0.25rem;
            font-weight: bold;

        }

        fieldset:focus-within {
            color: purple;
            border: 1px dashed rgba(128, 0, 128, 0.5);
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
        }

        .task-list label {
            margin-bottom: 0.5rem;
        }

        label input {
            vertical-align: middle;
        }

        textarea {
            height: 15rem;
            border-color: black;
            border-width: 0 0 0 0;
            padding: 0.5vw;
        }

        input {
            height: 2em;
            min-width: 2em;
            border-color: black;
            border-width: 1px;
        }

        input, textarea {
            font: 0.875em/1.2em "JetBrains Mono", "Roboto Mono", monospace;
            color: #111;
        }

        input[type=text] {
            border-width: 0 0 1px 0;
        }

        input[type=checkbox] {
            margin-right: 0.5rem;
        }

        h1 {
            margin: 0 auto 0.5rem;
            text-align: center;
            color: rgba(153, 153, 204, 0.5);
            text-shadow: 0 4px 4px #fff, 0 0px 0 rgba(128, 0, 128, 0.5), 0 4px 4px #fff;
            letter-spacing: -0.085em;
            font-size: 3rem;
            opacity: 0.875;
            line-height: 1em;
            font-weight: 100;
        }

        h1 span {
            color: rgba(99, 174, 227, 0.5);
        }

        .task-item {
            display: flex;
        }

        .task-item :last-child {
            flex: 1
        }


    </style>
</head>
<body>
<form method="post" hx-post="/" hx-trigger="input delay:1s" hx-swap="outerHTML">
    <h1>Three Things for <span class="date"><?= $list->date ?></span></h1>

    <fieldset class="task-list">
        <legend>Goals</legend>
        <label class="task-item">
            <input tabindex=90 type="checkbox" name="done1" id="done1" <?= $list->done1 ?>>
            <input tabindex=20 type="text" name="task1" id="task1" value="<?= $list->task1 ?>" <?= is_autofocus(
                $list['task1']
            ) ?>>
        </label>
        <label class="task-item">
            <input tabindex=91 type="checkbox" name="done2" id="done2" <?= $list->done2 ?>>
            <input tabindex=21 type="text" name="task2" id="task2" value="<?= $list->task2 ?>">
        </label>
        <label class="task-item">
            <input tabindex=92 type="checkbox" name="done3" id="done3" <?= $list->done3 ?>>
            <input tabindex=22 type="text" name="task3" id="task3" value="<?= $list->task3 ?>">
        </label>
    </fieldset>
    <fieldset class="meta">
        <legend>Notes</legend>
        <label class="task-item">
            <textarea tabindex=10 name="notes" id="notes"><?= $list->notes ?></textarea>
        </label>
    </fieldset>
</form>
</body>
</html>
