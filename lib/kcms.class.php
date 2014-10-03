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

  public function update($request) {

    $old = $this->getConfig('talko');
    $diff = $this->diff($request, $this->flatten($old));
    $new = $this->expand($request);

    return ['succes' => true, 'diff' => $diff, 'new' => $new];

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

  public function expand($flat) {

    $regex = '/('.preg_quote(self::oseparator).')|('.preg_quote(self::aseparator).')/';
    $test = 'copy::section::about::cast;;0::name';

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
        } elseif ($dims[$i-1] == self::aseparator) {
          $string .= '["'.$dims[$i].'"]';
        }

        if (isset($dims[$i+1]) && eval('return isset('.$string.');') == false) {
          if ($dims[$i+1] == self::oseparator) {
            eval($string.' = new \stdClass;');
          }
          if ($dims[$i+1] == self::aseparator) {
            eval($string.' = [];');
          }

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

}
