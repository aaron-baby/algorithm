<?php
$a = [3,8,1,2,15,15,11,20,31];
$max = 30;
$result = match($a, $max);
var_dump($result);
/**
 * 先对数组里的数分组
 * 比max大的
 * 比max/2大的一组
 * 比max/2小的一组
 * @return [type] [description]
 */
function half_group(array $a, $max){
	$surpass = array();
	$high = array();
	$middle = array();
	$low = array();
	arsort($a, SORT_NUMERIC);
	foreach ($a as $value) {
		if ($value>=$max) {
			$surpass[] = $value;
		} elseif ($value*2>$max) {
			$high[] = $value;
		} elseif ($value*2==$max) {
			$middle[] = $value;
		} else {
			$low[] = $value;
		}
	}
	return array($surpass, $high, $middle, $low);
}

function match(array $a, $max){
	$pack = array();
	list($surpass, $high, $middle, $low) = half_group($a, $max);
	if ($surpass) {
		foreach ($surpass as $value) {
			$pack[] = array($value);
		}
	}
	// 处理high数组
	if($high) {
		foreach ($high as $value) {
			$pack[] = get_company($value, $low, $max);
		}
	}
	// 如果有中值，看个数
	while ( !empty($middle) ){
		$item = array_shift($middle);
		if ($middle) {
			$pack[] = array($item, array_shift($middle));
		} else {
			$pack[] = get_company($item, $low, $max);
		}
	}
	// var_dump($low);exit;
	// 低维数组如果还有值
	while (count($low)>=2) {
		$pack[] = get_company([array_shift($low), array_shift($low)], $low, $max);
	}
	if ($low) {
		$pack[] = $low;
	}
	return $pack;
}


/**
 * 从池子里找到不超过最大值的同伴
 * 如果池子空了，直接返回最大值
 */
function get_company($big, array &$pool, $max){
	if ( is_numeric($big) ) {
		// echo $big . "\n";
		$result = array($big);
		$addition = $big;
	} else {
		$result = $big;
		$addition = $big[0] + $big[1];
	}
	if ( empty($pool) ) {
		return $result;
	}
	foreach ($pool as $k=>$value) {
		$sum = $addition+$value;
		// 如果和超过了最大值
		if ($sum>$max){
			continue;
		}
		$addition = $sum;
		// 放入袋子，并从池子里移出
		unset($pool[$k]);
		$result[] = $value;
	}
	return $result;
}



