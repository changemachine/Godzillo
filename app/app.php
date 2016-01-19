<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/zillow.php";
  date_default_timezone_set('America/Los_Angeles');
  setlocale(LC_MONETARY, 'en_US');
  $app = new Silex\Application();
  $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => '../views'));

//TODO:
// REST API TESTING: http://reqres.in/
// http://www.mindfiresolutions.com/Google-RETS-ZILLOW-API.htm
// http://www.mindfiresolutions.com/Google-RETS-ZILLOW-API.htm
// http://www.mindfiresolutions.com/Google-RETS-ZILLOW-API.htm

//Home
  $app->get("/", function() use ($app) {
    $today = date('l, F jS, Y');
      //PILLOW
    $key = 'X1-ZWz1f41r2rdsln_7hp6b';
    $s = new VerticalTab\Pillow\Service($key);
    $results = $s->getSearchResults('8125 NE Wygant St', '97218');
    $property = $results->current();

    return $app['twig']->render('home.twig', array('today' => $today));
  });

//Results
  $app->post("/results", function() use ($app) {
    $today = date('l, F jS, Y');
    $address = $_POST['address'];
    $citystate = $_POST['citystate'];

      //PILLOW
    $key = 'X1-ZWz1f41r2rdsln_7hp6b';
    $s = new VerticalTab\Pillow\Service($key);
    $results = $s->getSearchResults($address, $citystate);
    $urlParams = $s->getSearchResults($address, $citystate, true);
    $property = $results->current();
    //--------------------------------------
    $img = $property->chart->url;
    $url = "http://www.zillow.com".$urlParams;
    // $allData = json_encode(simplexml_load_string(file_get_contents($url)));



    $compsArray = [];
    foreach($property->comps as $comp) {
      array_push($compsArray, $comp);
      // $compLongLat = [$comp->longitude, $comp->latitude];
      // $compZPID = $comp->zpid;
      // $compEst = $comp->zestimate->amount;
      // $compDeets = SqFt, Beds/Baths;
    }
    $comps = json_encode($compsArray);



// DUMP -----------------------------------
// var_dump($comps);

    return $app['twig']->render('results.twig', array('today' => $today, 'property' => $property, 'img' => $img, 'comps' => $comps ));
  });

  return $app;
?>
