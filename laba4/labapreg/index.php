<?php
 echo preg_replace('#aaa(?=b)#', '!', 'aaab'); # echo preg_replace('#aaa(?<!b)#', '!', 'aaab aaab');
 preg_match_all('#ab{4,}a#', 'aa aba abba abbba abbbba abbbbba', $matches); print_r($matches[0]);
 preg_match_all('#ab[be]a#', 'aba aca aea abba adca abea', $matches); print_r($matches[0]);
 echo preg_replace('#(\w)\1#', '!', 'aae xxz 33a');
?>