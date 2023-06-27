<?php
// _  _ ____ _   _ ____ ____ ____ _  _ ____ ____ 
// |_/  |___  \_/  |___ |__/ |__| |\/| |___ [__  
// | \_ |___   |   |    |  \ |  | |  | |___ ___] 
// A utility function to convert from a readable array keyframe format into SVG animation data.

function findValue($target, &$keyframes) {
  $value = false;
  if (!is_null($keyframes[$target]["value"])) {
    $value = $keyframes[$target]["value"];
    $keyframes[$target]["hidden"] = false;
  } else {
    $keyframes[$target]["hidden"] = true;
    if ($target == 0) {
      $next = 1;
      do {
        if (!is_null($keyframes[$next]["value"])) {
          $value = $keyframes[$next]["value"];
        }
        $next++;
      } while ($value == false);
    } else {
      $value = $keyframes[$target - 1]["value"];
    }
    $keyframes[$target]["value"] = $value;
  }
  return $value;
}

function keyframes($keyframes, $delay = 0) {

  $has_hidden_keyframes = false;
  foreach ($keyframes as $index => $kf) {
    if (is_null($kf["value"])) {
      $has_hidden_keyframes = true;
    }
  }

  if ($delay != 0) {
    $keyframes[0]["easeE"] = E_LINEAR;
    array_unshift($keyframes, array(
      "value" => findValue(0, $keyframes),
      "length" => $delay,
      "easeS" => S_LINEAR,
    ));
  }

  $final_kf = count($keyframes) - 1;
  $total_length = 0;
  foreach ($keyframes as $index => $kf) {
    $total_length += ($index < $final_kf) ? $kf["length"] : 0;
  }

  $values = "";
  $keyTime = 0;
  $keyTimes = "";
  $keySplines = "";

  $prev_kf = "";
  foreach ($keyframes as $index => $kf) {
    $delimiter = ($index < $final_kf) ? "; " : "";
    $values .= findValue($index, $keyframes) . $delimiter;
    if ($index == $final_kf) {
      $keyTime = 1;
    } elseif (0 < $index) {
      $keyTime += round($prev_kf["length"] / $total_length, 3);
    }
    $keyTimes .= $keyTime . $delimiter;

    if (0 < $index) {
      $keySplines .= $prev_kf["easeS"] . " " . $kf["easeE"] . $delimiter;
    }

    if ($index < $final_kf) {
      $prev_kf = $kf;
    }    
  }

  $animation_data = 'calcMode="spline" ';
  $animation_data .= 'dur="' . $total_length . '" ';
  $animation_data .= 'values="' . $values . '" ';
  $animation_data .= 'keyTimes="' . $keyTimes . '" ';
  $animation_data .= 'keySplines="' . $keySplines . '"';

  if ($has_hidden_keyframes) {
    $animation_data .= ' /><animate attributeName="opacity" begin="indefinite" calcMode="discrete" ';
    $values = "";
    foreach ($keyframes as $index => $kf) {
      $delimiter = ($index < $final_kf) ? "; " : "";
      $values .= ($kf["hidden"] ? 0 : 1) . $delimiter;
    }
    $animation_data .= 'dur="' . $total_length . '" ';
    $animation_data .= 'values="' . $values . '" ';
    $animation_data .= 'keyTimes="' . $keyTimes . '" ';
  }

  echo $animation_data;

}

// Additional constants for spline values from https://easings.net/
// For each pair, the "S" constant should be provided as the easeS value,
// and the "E" constant should be used as the easeE value
define("S_LINEAR", "0 0");
define("E_LINEAR", "1 1");

define("S_IN_OUT_QUAD", "0.45 0");
define("E_IN_OUT_QUAD", "0.55 1");

define("S_IN_OUT_CUBIC", "0.65 0");
define("E_IN_OUT_CUBIC", "0.35 1");

define("S_IN_QUAD", "0.11 0");
define("E_IN_QUAD", "0.5 0");

define("S_OUT_QUAD", "0.5 1");
define("E_OUT_QUAD", "0.89 1");

define("S_IN_CUBIC", "0.32 0");
define("E_IN_CUBIC", "0.67 0");

define("S_OUT_CUBIC", "0.33 1");
define("E_OUT_CUBIC", "0.68 1");