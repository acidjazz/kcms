<?

namespace lib;

class kcms {

  public $adds = [];

  CONST oseparator = '::';
  CONST aseparator = ';;';

  public function getConfig($project) {

    global $cfg;

    $jsonfile = $cfg['projects'][$project]['json'];
    $root = $cfg['projects'][$project]['root'];

    $json = json_decode(file_get_contents($jsonfile));

    return $json->cfg;



  }

  public function flatten($object, $flat=[], $array=false, $dimension=false) {


    foreach ($object as $key=>$value) {

        $prepend = $dimension.self::oseparator;

        if ($array) {
          $prepend = $dimension.self::aseparator;

        }

        if (!$dimension) {
          $prepend = '';
        }

      if (is_object($value)) {


        $flat = $this->flatten($value, $flat, false, $prepend.$key);

      } elseif (is_array($value)) {

        $this->adds[$prepend.$key] = array_keys( (array) $value[0]);
        $flat = $this->flatten($value, $flat, true, $prepend.$key);

      } else {
        $flat[$prepend.$key] = $value;
      }

    }

    return $flat;

  }

  public function test() {

    $data = new \stdClass();


    $regex = '/('.preg_quote(self::oseparator).')|('.preg_quote(self::aseparator).')/';
    $test = 'copy::section::about::cast;;0::name';

    $dims = preg_split($regex, $test, -1, PREG_SPLIT_DELIM_CAPTURE);

    // odd php bug w/ capturing delimiters where a blank value is returned ..wtf..
    foreach ($dims as $k=>$v) {
      if ($v == '') {
        unset($dims[$k]);
      }
    }

    $dims = array_values($dims);

    $string = '$data->';

    for ($i = 0; $i <= count($dims); $i += 2) {

      if (!isset($dims[$i-1])) {
        $string .= $dims[$i];
      } elseif ($dims[$i-1] == self::oseparator) {
        $string .= '->'.$dims[$i];
      } else {
        $string .= '["'.$dims[$i].'"]';
      }

      $evaled = eval($string);
      if ($evaled == false) {
        hpr($string.' = "";');
        eval($string.' = "";');
      }
    }

    hpr($string);

    return $data;

  }

  public function expand($flat) {

    $regex = '/('.preg_quote(self::oseparator).')|('.preg_quote(self::aseparator).')/';
    $test = 'copy::section::about::cast;;0::name';

    global $data;
    $data = new \stdClass;

    foreach ($flat as $key=>$value) {

      $dims = preg_split($regex, $key, -1, PREG_SPLIT_DELIM_CAPTURE);

      // odd php bug w/ capturing delimiters where a blank value is returned ..wtf..
      foreach ($dims as $k=>$v) {
        if ($v == '') {
          unset($dims[$k]);
        }
      }

      $dims = array_values($dims);

      $string = '$data->';

      for ($i = 0; $i <= count($dims); $i += 2) {

        if (!isset($dims[$i-1])) {
          $string .= $dims[$i];
        } elseif ($dims[$i-1] == self::oseparator) {
          $string .= '->'.$dims[$i];
        } else {
          $string .= '["'.$dims[$i].'"]';
        }

        $evaled = eval('return isset('.$string.');');

        if ($evaled == false) {
          if (!isset($dims[$i+1])) {
            if (is_numeric($value)) {
              $assign = $string.' = '.$value.';';
            } else {
              $assign = $string.' = "'.$value.'";';
            }
            eval($assign);
          } else {
            eval($string.' = null;');
          }
        }
      }

    }

    return $data;

  }

  public function diff($old, $new) {

    $diffs = [];

    foreach ($old as $key=>$value) {
      if ($new[$key] != $old[$key]) {
        $diffs[$key] = [
          'before' => $old[$key],
          'after' => $new[$key]
        ];
      }
    }

    return $diffs;

  }

    /*
    foreach ($flat as $key=>$value) {

      $dims = explode('::', $key);

      foreach ($dims as $num=>$dim) {

        // root
        if ($num == 0) {

          if (is_object($data) && property_exists($data, $dim)) {
            $dimension = $data->$dim;
          } 

          if (is_array($data) && isset($data[$dim])) {
            $dimension = $data[$dim];
          } 

        } else {

          if (is_object($dimension) && property_exists($dimension, $dim)) {
            $dimension = $dimension->$dim;
          } 

          if (is_array($dimension) && isset($dimension[$dim])) {
            $dimension = $dimension[$dim];
          } 

        }

      }

      if ($dimension != $value) {
        $diffs[$key] = ['before' => $dimension, 'after' => $value];
      }

    }
     */

}
