<?php
require_once('job.php');

function myFunc($arg_1, $arg_2)
{
	echo "Hello, $arg_1 and $arg_2!";
}

function demo()
{
	/* 1. Define your job. */

	// Pass a string containing the name of the function
	$myJob = new AsyncJob("myFunc");

	/* 2. Run one or more jobs asynchronously. */

	$myJob->runOneWorker('George', 'Mary');
	$myJob->runOneWorker('John ', 'Cena');

	/* 3. Check your job status. */

	// Method 1: Non-blocking
	if ($myJob->workersAreRunning())
		// You can do something else

	// Method 2: Blocking
	$myJob->joinAllWorkers(); // Keep waiting until all jobs finished
}
