<?

namespace ctl;

class index {

  public function index() {

    global $cfg;

    $project = 'talko';
    $jsonfile = $cfg['projects']['talko']['json'];
    $root = $cfg['projects']['talko']['root'];

    $json = json_decode(file_get_contents($jsonfile));

    $data = $json->cfg;

    $ocms = new \lib\ocms();

    $flat = $ocms->flatten($data);

    \lib\jade::c('index', ['flat' => $flat, 'adds' => $ocms->adds]);

    hpr($flat);
    hpr($ocms->adds);

  }

}
