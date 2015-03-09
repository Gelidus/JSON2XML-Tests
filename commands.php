<?php

	$commands = array(
		"1" => [0, ""],
		"2" => [0, "-i -s"],
		"3" => [0, "-i"],
		"4" => [0, "-r=\"root\""],
		"5" => [0, "-r=\"some_shit\" -s -l"],
		"6" => [0, "-l -i -s"],
		"7" => [0, "-h=_ -l"],
		"8" => [0, "-h=qq -r=root --array-name=wow_array"],
		"9" => [0, "-r=my_root --item-name=a -i"],
		"10" => [0, "-n -a -t -l -i"],

		"11" => [1, "--help -s"],
		"12" => [1, "--help -h=_"],
		"13" => [2, ""],
		"14" => [2, "-r=root --h=_"],
		"15" => [1, "--start=5"],
		"16" => [1, "--start=abcd --index-items"],
		"17" => [1, "--start=-10 --index-items"],

		"18" => [4, ""],
		"19" => [4, "-h=_ -l -i"],
		"20" => [4, "-r=root"],
	);
?>