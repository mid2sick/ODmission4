<?php
use parallel\Runtime;
/*
 * Requirement and installation:
 * https://github.com/krakjoe/parallel/blob/develop/INSTALL.md
 */

class AsyncJob
{
	private $job;
	private $workers;

	function __construct($job)
	{
		$this->job = $job;
		$this->workers = array();
	}

	function runOneWorker()
	{
		$args = func_get_args();
		$runtime = new \parallel\Runtime();
		$worker = $runtime->start($this->job, $args);
		$this->workers[] = $worker;
	}

	function workersAreRunning()
	{
		foreach ($this->workers as $worker) {
			if (!$worker->done())
				return true;
		}
		return false;
	}

	function joinAllWorkers()
	{
		foreach ($this->workers as $worker) {
			while (!$worker->done())
				usleep(10000); # 0.01 second
		}
	}
}
