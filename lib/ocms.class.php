<?

namespace lib;

class ocms {

  public $adds = [];

  CONST separator = '->';

  public function flatten($object, $flat=[], $dimension='cfg') {

    foreach ($object as $key=>$value) {

      if (is_object($value)) {

        $flat = $this->flatten($value, $flat, $dimension.self::separator.$key);

      } elseif (is_array($value)) {

        $this->adds[$dimension.self::separator.$key] = array_keys( (array) $value[0]);
        $flat = $this->flatten($value, $flat, $dimension.self::separator.$key);

      } else {
        $flat[$dimension.self::separator.$key] = $value;
      }

    }

    return $flat;

  }

}
