<?php
function cosineSimilarity($tokensA, $tokensB)
{
    $a = $b = $c = 0;
    $uniqueMergedTokens = array_merge($tokensA, $tokensB);
    $x2 = 0;
    $y2 = 0;
    $xArray = array();
    $yArray = array();
    $address = 0;

    $nilaiAtas = 0;
    $x = $y = 0;

    var_dump("<table border=1 padding=2><thead><tr><td>Token</td><td>X</td><td>Y</td></tr></thead><tbody>");
    foreach ($uniqueMergedTokens as $token => $v) {
        $xArray[$address] = isset($tokensA[$token]) ?  $tokensA[$token] : 0;
        $yArray[$address] = isset($tokensB[$token]) ?  $tokensB[$token] : 0;

        // menghitung nilai atas
        $nilaiAtas += ($xArray[$address] * $yArray[$address]);
        $ax = ($xArray[$address] * $xArray[$address]);
        $ay = ($yArray[$address] * $yArray[$address]);
        $x += $ax;
        $y += $ay;
        var_dump("<tr><td>$token </td><td>" . $xArray[$address] . "</td><td>" . $yArray[$address] . "</td></tr>");
        $address++;
    }
    var_dump("</tbody>");
    var_dump("<tfoot><tr><td>Akar (a*a) </td><td>" . sqrt($x) . "</td><td>" . sqrt($y) . "</td></tfoot>");
    var_dump("</table>");
    $nilaiBawah = (sqrt($x) * sqrt($y));
    $hasil = $nilaiAtas / $nilaiBawah;
    var_dump("Nilai Atas  = " . $nilaiAtas . "</br>");
    var_dump("Nilai Bawah = " . $nilaiBawah . "</br>");
    var_dump("Hasilnya    = " . $hasil . "</br></br>");
    return $hasil;
}

function lblString($array = [])
{
    $str = '';
    foreach ($array as $val) {
        $str .= strval($val['label']) . ' ';
    }
    return $str;
}
