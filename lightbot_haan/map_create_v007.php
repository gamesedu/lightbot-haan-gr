<?php
$counter=0;
$counter_steps=0;
$max_lights=5;
$max_steps=4;
$debug=0;
$light_array= array("b", "l","b","b","b");
$steps_array= array(0, 1,0,0,0);
if($debug)print_r($light_array);

function getLight(){

	global $counter,$max_lights,$light_array,$debug;
	//$input = array("b", "l");

	$random=rand(0,4);

	$ret=$light_array[$random];

	if($ret=="l") $counter++;
	if($debug) echo "<h1>$counter :light:$ret ,random=$random</h1>";
	if ($counter>$max_lights) $ret="b";
	//getLight()
	return $ret;
}//function getLight(){ ///////////////////////////////

function getStep(){

	global $counter_steps,$max_steps,$steps_array,$debug;
	//$input = array("b", "l");

	$random=rand(0,4);

	$ret=$steps_array[$random];

	if($ret=="l") $counter_steps++;
	if($debug) echo "<h1>$counter_steps :light:$ret ,random=$random</h1>";
	if ($counter_steps>$max_steps) $ret="0";
	//getLight()
	return $ret;
}//function getStep(){ ////////////////////////////////////////////////////////////////


echo '
{"direction":0,"position": {"x": 0, "y": 5},
"map":
[[

{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"}
],[
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"}
],[
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"}
],[
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"}
],[
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"}
],[
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"},
{"h":'.getStep().', "t":"'.getLight().'"}

]],"medals":{"gold":5,"silver":10,"bronze":25}}

';

?>  