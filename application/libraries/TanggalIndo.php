<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TanggalIndo
{

  public function __construct()
  {
    $this->ci = &get_instance();
  }
  function init($tgl)
  {
    $tanggal = substr($tgl, 8, 2);
    $bulan = $this->_getBulan(substr($tgl, 5, 2));
    return $tanggal . " " . $bulan . " " . substr($tgl, 0, 4);
  }
  private function _getBulan($bln)
  {
    switch ($bln) {
      case 1:
        return "Jannuari";
        break;
      case 2:
        return "Februari";
        break;
      case 3:
        return "Maret";
        break;
      case 4:
        return "April";
        break;
      case 5:
        return "Mei";
        break;
      case 6:
        return "Juni";
        break;
      case 7:
        return "Juli";
        break;
      case 8:
        return "Agustus";
        break;
      case 9:
        return "September";
        break;
      case 10:
        return "Oktober";
        break;
      case 11:
        return "November";
        break;
      case 12:
        return "Desember";
        break;
    }
  }
}
