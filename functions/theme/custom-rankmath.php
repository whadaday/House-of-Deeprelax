<?php
/**
 * Filter to disable Table of Content test
 */
add_filter('rank_math/researches/tests', function ($tests, $type) {
	// unset($tests['contentHasTOC']);
	return $tests;
}, 10, 2 );