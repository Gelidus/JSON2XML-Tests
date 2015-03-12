<?php
  error_reporting(0);

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

    $shouldBeDoc = new DOMDocument();
    $isDoc = new DOMDocument();

    $shouldBeDoc->loadXML("<__ROOT__>" . str_replace("<?xml version=\"1.0\" encoding=\"UTF-8\"?>", "", $shouldBe) . "</__ROOT__>");
    $isDoc->loadXML("<__ROOT__>" . str_replace("<?xml version=\"1.0\" encoding=\"UTF-8\"?>", "", $is) . "</__ROOT__>");

    $shouldBe = json_decode(json_encode((array)simplexml_import_dom($shouldBeDoc)), true);
    $is = json_decode(json_encode((array)simplexml_import_dom($isDoc)), true);

    $delta = array_diff_assoc($shouldBe, $is);

    if (empty($delta)) {
      echo "[OK] Test " . $name . " passed\n";
      $good++;
    } else {
      echo "[ERR] Test " . $name . " failed\n";
      var_dump($delta);
      $bad++;
    }

    if ($cleanup) {
      unlink($tmpDir . $xmlName);
    }
  }

  echo "\nTotal passed/errors >> " . $good . "/" . $bad . " <<\n";
?>