<?php

namespace Threes;

require '../vendor/autoload.php';

use RedBeanPHP\R;

define('ROOT_DIR', __DIR__);
define('ROOT_URL',
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"]
);

# Bootstrap
header('Cache-Control: max-age=300');

R::setup('sqlite:data.db');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Daily Tasks</title>
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
			height:  10em;
			border-color:  black;
			border-width:  2px;
		}

		input {
			min-width:  2em;
			height:  2em;
			border-color:  black;
			border-width:  2px;
		}



	</style>
</head>
<body>
	<h1>Daily Tasks for <?= date("Y-m-d") ?></h1>

		<fieldset class="tasklist">
			<legend>Tasks</legend>
			<label class="task-item">
				<input type="checkbox" name="c1">
				<input id="c1t">
			</label>
			<label class="task-item">
				<input type="checkbox" name="c2">
				<input id="c2t">
			</label>
			<label class="task-item">
				<input type="checkbox" name="c3">
				<input id="c3t">
			</label>
		</fieldset>
		<fieldset class="meta">
			<legend>Notes</legend>
			<label class="task-item"><strong>Notes</strong>
				<textarea></textarea>
			</label>
		</fieldset>

</body>
</html>
