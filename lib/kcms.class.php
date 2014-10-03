<?

namespace lib;

class kcms {

  public $adds = [];
  public $project = false;

  CONST oseparator = '::';
  CONST aseparator = ';;';

  public function __construct($project) {
    $this->project = $project;
  }

  public function getConfig() {

    global $cfg;

    $jsonfile = $cfg['projects'][$this->project]['json'];
    $json = json_decode(file_get_contents($jsonfile));

    return $json->cfg;

  }

  public function update($request) {

    $old = $this->getConfig($this->project);
    $diff = $this->diff($request, $this->flatten($old));
    $new = $this->expand($request);

    $history = new \mdl\history();
    $history->project = $this->project;
    $history->old = $old;
    $history->new = $new;
    $history->diff = $diff;
    $history->save();

    $root = new \stdClass;
    $root->cfg = $new;
    $json = json_encode($root, JSON_PRETTY_PRINT);

    global $cfg;

    // safety
    $date = date("c");
    copy($cfg['projects'][$this->project]['json'], $cfg['projects'][$this->project]['json'].'.'.$date.'.old');

    file_put_contents($cfg['projects'][$this->project]['json'], $json);

    // run any scripts
    $folder = $cfg['projects'][$this->project]['folder'];
    foreach ($cfg['projects'][$this->project]['scripts'] as $script) {
      chdir($folder);
      exec($script);
    }

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
          'after' => $new[$key],
          'diff' => [
            'before' => $this->stringDiff($old[$key], $new[$key])[0],
            'after' => $this->stringDiff($old[$key], $new[$key])[1]
          ]
        ];
      }
    }

    return $diffs;

  }

  public function stringDiff($old, $new) {

    $from_start = strspn($old ^ $new, "\0");        
    $from_end = strspn(strrev($old) ^ strrev($new), "\0");

    $old_end = strlen($old) - $from_end;
    $new_end = strlen($new) - $from_end;

    $start = substr($new, 0, $from_start);
    $end = substr($new, $new_end);
    $new_diff = substr($new, $from_start, $new_end - $from_start);  
    $old_diff = substr($old, $from_start, $old_end - $from_start);

    return [$new_diff, $old_diff];
    


  }

}
