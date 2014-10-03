<?

namespace ctl;

class update extends \lib\api {

  public function index() {

    $kcms = new \lib\kcms();

    $data = $kcms->getConfig('talko');
    $flat = $kcms->flatten($data);

    $diff = $kcms->diff($flat, $_REQUEST);
    $new = $kcms->expand($_REQUEST);

    $this->result(true, 'success', ['diff' => $diff, 'update' => $new]);

  }

}
