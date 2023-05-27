<div class="col-md-10">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Temperature</th>
        <th scope="col">Humadity</th>
        <th scope="col">Log</th>
      </tr>
    </thead>
    <tbody>
      <?php if (isset($obj)) : ?>
        <?php foreach ($obj as  $value) : ?>
          <tr>
            <td scope="row"><?= $value['id']  ?></td>
            <td scope="row"><?= $value['temperature'] ?>° C</td>
            <td scope="row"><?= $value['humidity']  ?> %</td>
            <td scope="row"><?= $value['created_at'] ?></td>
          </tr>
        <?php endforeach ?>
      <?php else : ?>
        <tr>
          <td scope="row" colspan="34" class="text-danger text-center">Cari Terlebih dahulu</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>