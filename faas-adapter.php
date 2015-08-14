<?php

class Faas {

protected static $named_repos;

protected static function load() {

  $repos = json_decode(file_get_contents('https://raw.githubusercontent.com/dobryakov/faas/master/list.json'), true);

  foreach ($repos['repository'] as $repo) {

    $name = $repo['name'];
    self::$named_repos[$name] = $repo;

  }

}

public static function call($name, $data) {

    if (!self::$named_repos) { self::load(); }

    if (in_array($name, array_keys(self::$named_repos))) {

      $url = self::$named_repos[$name]['endpoint'] . '?' . http_build_query($data); 

      $result = json_decode(file_get_contents($url), true);
      
      if (!empty($result['result'])) {
        return $result['result'];
      }

    } else {
      throw new Exception('Faas service ' . $name . ' not found');
    }

}

}

print_r( Faas::call( 'dobryakov/validates-email', array('email' => 'test@somedomain.com') ) );
