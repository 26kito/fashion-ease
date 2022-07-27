<?php

function rupiah($num) {
    $result = 'Rp.'.number_format($num,0,',','.');
    return $result;
}

