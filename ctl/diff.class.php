<?

namespace ctl;

class diff extends \lib\api {

  public function index() {
    $kcms = new \lib\kcms('talko');
    $diff = $kcms->diff($kcms->flatten($kcms->getConfig()), $_REQUEST);
    $this->result(true, 'success', $diff);
  }

}
