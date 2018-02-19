<?php
/**
 * Created by PhpStorm.
 * Date: 15.02.2018
 * Time: 9:23
 */
header("Content-type: text/html; charset=utf-8");

include_once "../templates/head.html";

if (($_SERVER['REQUEST_METHOD'] == 'POST') && (count($_REQUEST) > 0)) {

    $string = $_REQUEST['string'];
    $stringAsArray = convertToArray($string);

    if (palindrome($stringAsArray)) {
        $output = $string;
    }
    else {
        $arrayOfPalindromes = seekSubPalindromes($stringAsArray);
        if ($longerSubPalindrome = longerSubPalindrome($arrayOfPalindromes)){
            $output = implode($longerSubPalindrome);
        } else {
            $string = mb_convert_encoding($string, "UTF-8", mb_detect_encoding($string));
            $output = mb_substr($string, 0, 1);
        }
    }
}


/*
 * Функция convertToArray($string) - преобразует строку в массив,
 * удаляя пробелы, приводя к нижнему регистру.
 */
function convertToArray($string) {
    $str = mb_strtolower($string);
    $str = str_replace(" ", "", $str);

    return $stringAsArray = preg_split('//u', $str, null, PREG_SPLIT_NO_EMPTY);
}

/*
 * Функция palindrome($stringAsArray) - определяет является ли строка палиндромом,
 * работакет со сторакми преобразованными в массив.
 */
function palindrome($stringAsArray) {
    $normalString = implode($stringAsArray);
    $reverseString = implode(array_reverse($stringAsArray));

    return ($normalString == $reverseString) ? true : false;
}

/*
 * Поиск под-палиндромов в строке, с учетом того что могут встречаться палиндромы
 * с удвоенными буквами в середине слова.
 */
function seekSubPalindromes($stringAsArray) {
    $str = [];
    $subPalindromes = [];
    $isDouble = false;
    for ($i = 1; $i < count($stringAsArray); $i++) {
        $k = $i + 1;
        $str[] = $stringAsArray[$i];
        for ($j = $i - 1; $j >= 0; $j--,$k++) {
            if ($stringAsArray[$i] == $stringAsArray[$k]) {
                $str[] = $stringAsArray[$k];
                $isDouble = true;
            } else {
                if ($isDouble) {
                    array_unshift($str, $stringAsArray[$j + 1]);
                } else {
                    array_unshift($str, $stringAsArray[$j]);
                }
                    $str[] = $stringAsArray[$k];
                    if (palindrome($str)) {
                        $subPalindromes[] = $str;
                    } else {
                        $str = [];
                        $isDouble = false;
                        break;
                }
            }
        }
    }
    return $subPalindromes;
}

/*
 * Возвращает самый длинный палиндром из массива палиндромов.
 */
function longerSubPalindrome($arrayOfPalindromes) {
    $longerSubPalindrome = $arrayOfPalindromes[0];
    for ($i = 1; $i < count($arrayOfPalindromes); $i++) {
        if (count($arrayOfPalindromes[$i]) > count($longerSubPalindrome))
            $longerSubPalindrome = $arrayOfPalindromes[$i];
    }
    return $longerSubPalindrome;
}

?>

<form action="">
    <p>Вывод:</p>
    <p class="out"><?= $output; ?></p>
</form>

<?php
include_once "../templates/foot.html";

?>
