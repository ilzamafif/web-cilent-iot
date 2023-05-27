<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Real-Time Sensor Data</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    .gauge {
      width: 100%;
      max-width: 250px;
      font-family: "Roboto", sans-serif;
      font-size: 32px;
      color: #004033;
    }

    .gauge__body {
      width: 100%;
      height: 0;
      padding-bottom: 50%;
      background: #b4c0be;
      position: relative;
      border-top-left-radius: 100% 200%;
      border-top-right-radius: 100% 200%;
      overflow: hidden;
    }

    .gauge__fill {
      position: absolute;
      top: 100%;
      left: 0;
      width: inherit;
      height: 100%;
      background: #009578;
      transform-origin: center top;
      transform: rotate(0.25turn);
      transition: transform 0.2s ease-out;
    }

    .gauge__cover {
      width: 75%;
      height: 150%;
      background: #ffffff;
      border-radius: 50%;
      position: absolute;
      top: 25%;
      left: 50%;
      transform: translateX(-50%);

      /* Text */
      display: flex;
      align-items: center;
      justify-content: center;
      padding-bottom: 25%;
      box-sizing: border-box;
    }
  </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.4.1/socket.io.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.0.4/socket.io.js" integrity="sha512-aMGMvNYu8Ue4G+fHa359jcPb1u+ytAF+P2SCb+PxrjCdO3n3ZTxJ30zuH39rimUggmTwmh2u7wvQsDTHESnmfQ==" crossorigin="anonymous"></script>
  <script>
    const socket = io.connect("http://192.168.1.1:5010");

    socket.on('connect', function() {
      console.log('Connected to server');
    });

    socket.on('disconnect', function() {
      console.log('Disconnected from server');
    });

    socket.on('sensor_data', function(data) {
      // Menerima data sensor dari server
      console.log(data);
      // Lakukan operasi untuk menampilkan data di HTML, misalnya dengan mengubah nilai elemen <p>
      document.getElementById('sensor-value').textContent = 'Temperature: ' + data[0]['temperature'] + '°C, Humidity: ' + data[0]['humidity'] + '%';
    });
  </script>
</head>

<body>
  <p id="sensor-value"></p>
  <?php
  function get_sensor($url)
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  function post_command($url, $command)
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\n\t\"command\":\"$command\"\n}",
      CURLOPT_HTTPHEADER => array(
        "content-type: application/json"
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  $requestData = get_sensor("http://192.168.1.26:5010/web_sensor");
  $obj = json_decode($requestData, true);
  // var_dump($obj);
  $nilai_sensor_temperature = $obj[0]['temperature'];
  $nilai_sensor_humidity = $obj[0]['humidity'];
  ?>

  <div class="container mt-5">
    <div class="row text-center align-items">
      <h5>Web Monitoring Suhu Dan Kelembapan</h5>
      <p><?= $nilai_sensor_temperature ?></p>
      <p><?= $nilai_sensor_humidity ?></p>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-2 mb-5">
        <div class="gauge temperature">
          <div class="gauge__body">
            <div class="gauge__fill temperature_fill"></div>
            <div class="gauge__cover temperature_cover"></div>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="gauge humidity">
          <div class="gauge__body">
            <div class="gauge__fill humidity_fill" style="background: red;"></div>
            <div class="gauge__cover humidity_cover"></div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <script>
    const gaugeElementTemperature = document.querySelector(".temperature");
    const gaugeElementHumidity = document.querySelector(".humidity");

    function setGaugeValue(gauge, value) {
      if (value < 0 || value > 1) {
        return;
      }

      gauge.querySelector(".temperature_fill").style.transform = `rotate(${ value / 2 }turn)`;
      gauge.querySelector(".temperature_cover").textContent = `${Math.round(value * 100)} c`;
    }

    function setGaugeHumidity(gauge, value) {
      if (value < 0 || value > 1) {
        return;
      }

      gauge.querySelector(".humidity_fill").style.transform = `rotate(${ value / 2 }turn)`;
      gauge.querySelector(".humidity_cover").textContent = `${Math.round(value * 100)}%`;
    }

    setGaugeValue(gaugeElementTemperature, 0.65);
    setGaugeHumidity(gaugeElementHumidity, 0.3);
  </script>
</body>

</html>