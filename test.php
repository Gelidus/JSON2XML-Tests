<?php
  $script = "../jsn.php";
  $testDir = getcwd() . "/";
  $tmpDir = $testDir . "tmp/";

  $tests = array_diff(scandir($testDir . "tests"), array('..', '.'));
  $results = array_diff(scandir($testDir . "results"), array('..', '.'));
  include($testDir . "commands.php");

  $cleanup = $argv[1] == "clean";

  $total = count($commands);
  $good = 0; $bad = 0;

  foreach ($commands as $name => $opts) {
    $jsonName = $name . ".json";
    $xmlName = $name . ".xml";
    $code = $opts[0];
    $options = $opts[1];

    $paths = " --input=" . $testDir . "tests/" . $jsonName . " --output=" . $tmpDir . $xmlName;
    $cmd = "php -d open_basedir=\"\" " . $script . $paths . " " . $options; # get options from command

    $errOutput = exec($cmd, $execOutput, $returnCode);

    if ($returnCode != $code) { # code from commands
      echo "[ERR] Test " . $name . " failed with code: " . $returnCode . " expected: " . $code . "\n";
      $bad++;
      continue;
    } else if ($returnCode == $code && $returnCode != 0) {
      echo "[OK] Test " . $name . " passed\n";
      $good++;
      continue;
    }

    $shouldBe = preg_replace("/\n|\ |\t/", "", file_get_contents($testDir . "results/" . $xmlName));
    $is = preg_replace("/\n|\ |\t/", "", file_get_contents($tmpDir . $xmlName));

    if (strtolower($shouldBe) == strtolower($is)) {
      echo "[OK] Test " . $name . " passed\n";
      $good++;
    } else {
      echo "[ERR] Test " . $name . " failed\n";
      $bad++;
      echo shell_exec("diff " . $testDir . "results/" . $xmlName . " " . $tmpDir . $xmlName);
    }

    if ($cleanup) {
      unlink($tmpDir . $xmlName);
    }
  }

  echo "\nTotal passed/errors >> " . $good . "/" . $bad . " <<\n";
?>