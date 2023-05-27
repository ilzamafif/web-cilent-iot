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

function get_search($url, $id_perangkat, $tanggal, $tanggala)
{
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url . "/" . $id_perangkat  . "/" . $tanggal . "/" . $tanggala,
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

$url = "http://192.168.1.26:5010/search";
if (isset($_POST['cari'])) {
  $tglawal = $_POST['tglawal'];
  $tglakhir = $_POST['tglakhir'];
  $id_perangkat = $_POST['id_perangkat'];
  $tttt = get_search($url, $id_perangkat, $tglawal, $tglakhir);
  $obj = json_decode($tttt, true);
}

$requestData = get_sensor("http://192.168.1.26:5010/web_sensor");
$items = json_decode($requestData, true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Web Cilent</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script>
    // $(document).ready(function() {
    //   setInterval(function() {
    //     $("#row").load("view.php");
    //   }, 10000);
    // });
  </script>
</head>

<body>
  <div class="container mt-5">
    <div class="row text-center align-items mb-4 justify-content-center">
      <h2 class="mb-4 mt-md-4">Web Monitoring Suhu Dan <br> Kelembapan</h2>
      <div class="container">
        <form action="" method="post">
          <div class="row justify-content-center">
            <div class="col-md-3">
              <label for="Id Perangkat" class="form-label"><b>Id Perangkat</b></label>
              <select type="text" class="form-select" required id=" Id Perangkat" name="id_perangkat">
                <?php foreach ($items as  $value) { ?>
                  <tr>
                    <option value="<?= $value['id_perangkat'] ?>"><?= $value['id_perangkat']  ?></option>
                  </tr>
                <?php } ?>
              </select>
            </div>
            <div class="col-md-3">
              <label for="tanggal" class="form-label">Tanggal</label>
              <input type="datetime-local" class="form-control" id="tanggal" name="tglawal">
            </div>
            <div class="col-md-3">
              <label for="s/d" class="form-label">S/D</label>
              <input type="datetime-local" class="form-control" id="s/d" name="tglakhir">
            </div>
            <div class="col-md-1 mt-4">
              <button type="submit" class="btn btn-primary mt-2" name="cari">Cari</button>
            </div>
          </div>
        </form>
      </div>

    </div>
    <div class="row justify-content-center" id="row">
      <?php include 'view.php'; ?>
    </div>
  </div>
</body>

</html>