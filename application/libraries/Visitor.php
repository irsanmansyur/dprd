<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visitor
{
  public $ci;
  private $os, $browser, $ip;
  public function __construct()
  {
    $this->ci = &get_instance();
    $this->ci->load->library('user_agent');

    $this->ip = $this->ip_user();
    $this->os = $this->ci->agent->platform();
    $this->browser = $this->ci->agent->browser();

    $this->setVisitor();
    //dd
  }

  function ip_user()
  {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
      $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
      $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }

  function setVisitor()
  {
    $data = [
      'ip' => $this->ip,
      'browser' => $this->browser,
      'os' => $this->os
    ];
    $this->ci->load->model('visitor_m');
    $duration = time() + 60 * 60 * 24;
    if (!isset($_COOKIE['visitorku'])) {
      $visit = $this->ci->visitor_m->get($data);
      if ($visit->num_rows() > 0) {
        $visit = $visit->row();
        if (time() > $visit->date_created) {
          $data['date_created'] = $duration;
          $this->ci->visitor_m->insert($data);
        }
      } else {
        $data['date_created'] = $duration;
        $this->ci->visitor_m->insert($data);
      }

      $browser = $this->ci->agent->browser();
      // simpan kedalam cookie browser

      setcookie("visitorku", $this->browser, $duration);
    }
  }
}
