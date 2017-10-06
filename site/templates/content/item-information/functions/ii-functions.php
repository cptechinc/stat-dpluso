<?php

	function generatecelldata($type, $parent, $column, $extracelldata) {
		$celldata = '';
		if ($type == 'D') {
			if (strlen($parent[$column['id']]) > 0) {$celldata = date($column['date-format'], strtotime($parent[$column['id']]));} else {$celldata = $parent[$column['id']];}
		} elseif ($type == 'N') {
			if (is_string($parent[$column['id']])) {
				$celldata = number_format(floatval($parent[$column['id']]), $column['after-decimal']);
			} else {
				$celldata = number_format($parent[$column['id']], $column['after-decimal']);
			}

		} else {
			$celldata = $parent[$column['id']];
		}
		if ($extracelldata) {
			$celldata .= $extracelldata;
		}
		return $celldata;
	}
