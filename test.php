<?php
  //error_reporting(0);
  include("XMLdiff/src/XmlDiff.php");

  $script = "../jsn.php";
  $testDir = getcwd() . "/";
  $tmpDir = $testDir . "tmp/";

  $tests = array_diff(scandir($testDir . "tests"), array('..', '.'));
  $results = array_diff(scandir($testDir . "results"), array('..', '.'));
  include($testDir . "commands.php");

  $cleanup = isset($argv[1]) && $argv[1] == "clean";

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

    $shouldBe = file_get_contents($testDir . "results/" . $xmlName);
    $is = file_get_contents($tmpDir . $xmlName);

    $shouldBe = "<__ROOT__>" . str_replace("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n", "", $shouldBe) . "</__ROOT__>" ;
    $is = "<__ROOT__>" . str_replace("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n", "", $is) . "</__ROOT__>";

    $shouldBe = new DOMDocument($shouldBe);
    $is = new DOMDocument($is);

    $diff = new XmlDiff($shouldBe, $is);

    $result = $diff->diff();
    if ($result->__toString() == "") {
      echo "[OK] Test " . $name . " passed\n";
      $good++;
    } else {
      echo "[ERR] Test " . $name . " failed\n";
      echo $result->__toString() . "\n";
      $bad++;
    }

    if ($cleanup) {
      unlink($tmpDir . $xmlName);
    }
  }

  echo "\nTotal passed/errors >> " . $good . "/" . $bad . " <<\n";
?>