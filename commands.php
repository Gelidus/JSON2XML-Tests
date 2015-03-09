<?php

	$commands = array(
		"1" => array(0, ""),
		"2" => array(0, "-i -s"),
		"3" => array(0, "-i"),
		"4" => array(0, "-r=\"root\""),
		"5" => array(0, "-r=\"some_shit\" -s -l"),
		"6" => array(0, "-l -i -s"),
		"7" => array(0, "-h=_ -l"),
		"8" => array(0, "-h=qq -r=root --array-name=wow_array"),
		"9" => array(0, "-r=my_root --item-name=a -i"),
		"10" => array(0, "-n -a -t -l -i"),

		"11" => array(1, "--help -s"),
		"12" => array(1, "--help -h=_"),
		"13" => array(2, ""),
		"14" => array(1, "-r=root --h=_"),
		"15" => array(1, "--start=5"),
		"16" => array(1, "--start=abcd --index-items"),
		"17" => array(1, "--start=-10 --index-items"),

		"18" => array(4, ""),
		"19" => array(4, "-h=_ -l -i"),
		"20" => array(4, "-r=root"),

		"21" => array(50, "-r=\"<root>\""),
		"22" => array(50, "-r=\"&name\""),
		"23" => array(50, "-r=root --array-name=\"<array>\""),
		"24" => array(50, "-r=root --item-name=-and"),
		"25" => array(51, "-h=-"),
	);
?>