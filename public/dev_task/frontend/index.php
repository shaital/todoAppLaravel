<?php
if (isset($_GET['saveorder']) && $_GET['saveorder'] == 1) {
  if (isset($_POST['allAttributes']) && !empty($_POST['allAttributes'])) {
    $myfile = fopen(getcwd() . "/images.txt", "w") or die("Unable to open file!");
    fwrite($myfile, implode("\n", $_POST['allAttributes']));
    fclose($myfile);
    echo json_encode(['success' => true]);
    exit();
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hooray CashBack</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <style>
    #sortable {
      list-style-type: none;
      margin: 0;
      padding: 0;
    }

    #sortable li {
      margin: 3px 3px 3px 0;
      padding: 1px;
      float: left;
      width: 180px;
      height: 180px;
      text-align: center;
      display: flex;
      justify-content: center;
    }

    .center {
      width: 960px;
      margin: 0 auto;
    }

    img {
      width: 120px;
      margin: auto;
      display: block;
    }

    #save {
      display: block;
      border-radius: 20px;
      background: blue;
      width: 300px;
      margin: 10px auto;
      color: #fff;
    }
  </style>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    $(function() {
      $("#sortable").sortable();
      $("#sortable").disableSelection();
    });
  </script>
</head>

<body>
  <div class="center">
    <ul id="sortable">
      <?php
      $url =  str_replace('index.php', '', "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
      $directory = getcwd() . "/images";

      if (file_exists(getcwd() . "/images.txt")) {
        $result = file_get_contents(getcwd() . "/images.txt");
        $images = explode("\n", $result);
      } else {
        $images = glob($directory . "/*.{jpg,JPG,jpeg,JPEG,png,PNG}", GLOB_BRACE);
      }



      foreach ($images as $image) {
        $img = $url . '/images//' . basename($image);
        echo '<li class="ui-state-default" data-img="' . basename($image) . '"><img src="' . $img . '"/></li>';
      }

      ?>

    </ul>
    <div style="clear:both;"></div>
    <button id="save">Save</button>
  </div>
  <script>
    $("button").click(function() {
      var allAttributes = $('li').map(function() {
        return $(this).attr('data-img');
      }).get();

      $.ajax({
        url: "<?php echo $url; ?>/index.php?saveorder=1",
        method: "POST",
        data: {
          allAttributes
        },
        dataType: 'json',
        success: function(result) {
          if (result.success) {
            alert('נשמר בהצלחה!');
          }
        }
      });
    });
  </script>
</body>

</html>