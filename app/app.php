<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/zillow.php";
  date_default_timezone_set('America/Los_Angeles');
  setlocale(LC_MONETARY, 'en_US');

  $app = new Silex\Application();
  $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => '../views'));

//
  // function searchFor($addr, $city){
  //     $zillow_id = 'X1-ZWz1f41r2rdsln_7hp6b';
  //   //Get search
  //     $address = urlencode($addr);
  //     $citystatezip = urlencode($city);
  //   //Build search into a url
  //     $url = "http://www.zillow.com/webservice/GetSearchResults.htm?zws-id=$zillow_id&address=$address&citystatezip=$citystatezip";
  //   // API request, response
  //     $result = file_get_contents($url);
  //     $data = simplexml_load_string($result);
  // }

function priceFormat($number){
  $price = money_format('%i', $number) . "\n";
}

//Home
  $app->get("/", function() use ($app) {
    $today = date('l, F jS, Y');
      //PILLOW
    $key = 'X1-ZWz1f41r2rdsln_7hp6b';
    $s = new VerticalTab\Pillow\Service($key);
    $results = $s->getSearchResults('8125 NE Wygant St', '97218');
    $property = $results->current();

    // "Results:" . PHP_EOL;
    // echo "Zillow ID:  " . $property->zpid . PHP_EOL."<br>";
    // echo "City:       " . $property->city . PHP_EOL."<br>";
    // echo "Estimate:  $" . $property->zestimate->amount . PHP_EOL."<br>";
    // echo "<img src='" . $property->chart->url . "'></img><br>";

    return $app['twig']->render('home.twig', array('today' => $today));
  });

//Results
  $app->post("/result", function() use ($app) {
    $today = date('l, F jS, Y');
    $address = $_POST['address'];
    $citystate = $_POST['citystate'];

      //PILLOW
    $key = 'X1-ZWz1f41r2rdsln_7hp6b';
    $s = new VerticalTab\Pillow\Service($key);
    $results = $s->getSearchResults($address, $citystate);
    $property = $results->current();

    $img = $property->chart->url;
    // var_dump($property->chart->url);

    return $app['twig']->render('results.twig', array('today' => $today, 'property' => $property, 'img' => $img));
  });

  return $app;
?>
