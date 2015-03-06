<?php
	$script = "../jsn.php";
	$testDir = getcwd() . "/";
	$tmpDir = $testDir . "tmp/";

	$tests = array_diff(scandir($testDir . "tests"), array('..', '.'));
	$results = array_diff(scandir($testDir . "results"), array('..', '.'));
	include($testDir . "commands.php");

	foreach ($tests as $test) {
		$realName = explode(".", $test)[0]; # name without extension

		$paths = " --input=" . $testDir . "tests/" . $test . " --output=" . $tmpDir . $realName . ".xml ";
		$cmd = "php " . $script . $paths . $commands[$realName][1]; # get options from command

		$errOutput = exec($cmd, $execOutput, $code);

		if ($code != $commands[$realName][0]) { # code from commands
			echo "[ERR] Test " . $test . "failed with code: " . $code . " expected: " . $commands[$realName][0] . "\n";
			continue;
		}

		$shouldBe = preg_replace("/\n|\ |\t/", "", file_get_contents($testDir . "results/" . $realName . ".xml"));
		$is = preg_replace("/\n|\ |\t/", "", file_get_contents($tmpDir . $realName . ".xml"));

		if ($shouldBe == $is) {
			echo "[OK] Test " . $test . " passed\n";
		}
	}
?>