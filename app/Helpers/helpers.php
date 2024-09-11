<?php

if (!function_exists('formatirajCijenu')) {
  /**
   * Formatira cifru iz baze podataka.
   *
   * @param float $cijena
   * @param int $decimale
   * @param string $decimalniSeparator
   * @param string $separatorHiljada
   * 
   * @return string
   */
  function formatirajCijenu($cijena, $decimale = 2, $decimalniSeparator = ',', $separatorHiljada = '.')
  {
    return number_format(round($cijena / 100, $decimale), $decimale, $decimalniSeparator, $separatorHiljada);
  }
}