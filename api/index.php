<?php
  //header("Location: curlconsumer/consumer.php");
  //header("Location: phpprotect_obfuscated/curlconsumer/consumer.php");
  //header("Location: http://127.0.0.1:8888/projects/luzentuvida/api/v0/readings/max_XSLT/8");
  function showGroup($groupName, $title, $baseref, $group) {
      echo("<li>" . $groupName );
      echo("<ul>");

      foreach ($group as $url) {

          echo("<li>");
        //  for ($i = 0; $i <= 2; $i++) {
          for ($i = 0; $i <= 0; $i++) {
              if($i == 0){
                $format = '';
                $urlName = $url;
              } elseif($i == 1) {
                $format = '&format=xml';
                $urlName = 'xml';
              } elseif($i == 2) {
                $format = '&format=xsl';
                $urlName = 'xsl';
              }

              echo ('<a title="' . $baseref . $url . $format .
                    '" href="' . $baseref . $url . $format .'">' . $urlName . '</a>&nbsp;&nbsp;||&nbsp;&nbsp;');
          }
          echo("</li>");
      }
      echo("</li>");
      echo("</ul>");
      unset($valor);

  }

  $title = 'Local API';
  $baseref = 'http://127.0.0.1/jumanja.net/sabores/api/v1.5.3/';
  if(isset($_POST["type"])){
    if($_POST["type"] == "Web"){
      $title = 'Web API';
      $baseref = 'https://jumanja.net/sabores/api/v1.5.3/';
    }
  }
?>
<head>
<title><?=$title; ?></title>
</head>

<body>
<form action="index.php" method="POST">
<h2><?=$title; ?></h2>
<ul>
<li><a title="<?=$baseref; ?>echo" href="<?=$baseref; ?>echo">echo</a></li>
<li>URLs
  <ul>
<?php
  showGroup("books", $title, $baseref,
      array(
        'books/count?',
        'books?&max=8',
        'books?',
        'books?fields=libro,titulo',
        'books?&sort=-titulo&fields=titulo,libro,idreg',
        'books?fields=idreg,libro,titulo&sort=titulo')
   );

  showGroup("bible", $title, $baseref,
      array(
        'bible/count?',
        'bible?&max=8',
        'bible/books/AT?&max=8&fields=libro,capit,versini,versfin,contenido',
        'bible/books/NT?&max=8&fields=libro,capit,versini,versfin,contenido',
        'bible/books/EV?&max=8&fields=libro,capit,versini,versfin,contenido',
        'bible/books/Jn?&max=8&fields=libro,capit,versini,versfin,contenido',
        'bible/books/1 Co?&max=8&fields=libro,capit,versini,versfin,contenido',
        'bible/books/Lc?&fields=libro,capit,versini,versfin,contenido&chapter=1&verse=37',
        'bible/books/Lc?&fields=libro,capit,versini,versfin,contenido&chapter=1&verseFrom=46&verseTo=55'
        )
   );

  showGroup("phrases", $title, $baseref,
      array(
        'phrases/count?',
        'phrases?&max=8',
        'phrases/empty'
        )
   );

  showGroup("readings", $title, $baseref,
      array(
        'readings/day?',
        'readings/codes?date=2015-02-13',
        'readings/codes?date=2015-09-13',
        'readings/codes?date=2015-12-08',
        'readings?date=2015-02-13',
        'readings?date=2015-09-13',
        'readings?date=2015-12-08',
        'readings?'
        )
   );

?>
  </ul>
</li>

</ul>
<?php
  if(isset($_POST["type"]) && $_POST["type"] == "Web"){
    echo('Cambiar API a :<input id="type" name ="type" type="submit" value="Local">');
  } else {
    echo('Cambiar API a :<input id="type" name ="type" type="submit" value="Web">');
  }
?>
</form>
</body>
