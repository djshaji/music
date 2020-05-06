<script>
  song = null
  song_id = null
</script>
<?php
  $toplevel = true ;
  if ($album == null) {
    $toplevel = false ;
    $album = $_GET ["album"] ;
  }

  $contents = scandir ("media/") ;
  // $songs = [] ;
  if (! $toplevel)
    require_once ("ui/header.php") ;
  $song = $_GET ['song'] ;
  $songs = null ;
  if ($song != null) {
    foreach ($contents as $c) {
      $d = scandir ('media/' . $c) ;
      foreach ($d as $e) {
        // array_push ($songs, 'media/' . $c . '/'. $e) ;
        // print (strpos (strtolower ($e), $song));
        // print (strpos (strtolower ($e), $song)) ;
        if (strpos (strtolower ($e), $song) !== false) {
          // print ($e) ;
          printf ("<script>var song = 'media/%s/%s'</script>", $c,$e) ;
          $album = $c ;
          break ;
        }
      }
    }
  
  
    // var_dump ($songs) ;
  }

  // $contents = scandir ("media") ;
  foreach ($contents as $c) {
    if (strpos (strtolower ($c), $album) !== false) {
      $album = $c ;
      break ;
    }
  }

  // echo $album ;
  $str = file_get_contents('media/' . $album . '/info.json') ;
  $json = json_decode($str, true);
  // var_dump ($json);
  $dir = $album ;
  $album = $json ["album"] ;
  $album_js = album_format_title ($album) ;
  $year = $json ["year"] ;
  $cover = $json ["cover"] ;
  $description = $json ["description"] ;

  // $tracks = scandir ('media/' . $dir) ;
  // sort by last modified
  $tracks = glob('media/' . $dir . '/*') ;
  usort($tracks, function($x, $y) {
    return filemtime($x) < filemtime($y);
  });

  // print ($album) ;
  // var_dump ($tracks) ;
  printf ('<script>var %s = [];', $album_js);
  // printf ('var song = "%s";', $song);
  // foreach ($tracks as $t) {
  // }

  printf (";console.log ('Album name:  %s');</script>", $album_js);
?>

<!-- container 
<div 
  <?php 
    // print ("toplevel") ;
    // var_dump ($toplevel);
  //   if ($toplevel == true) 
  //     echo 'class="container-fluid"' ;
  //   else
  //     echo 'class="container-fluid"' ;

  //   printf ('id="%s', $album_js . '-container') ;
   ?>
> -->


<div class="card shadow p-3 mb-5 bg-white rounded m-3">
  <h5  class="card-header"><?php if (!$toplevel)echo $album ; ?>
    <button class="btn btn-link" type="button"   
      <?php 
        if ($toplevel) 
          printf ('data-toggle="collapse" data-target="#%s-collapse" aria-expanded="true" aria-controls="%s-collapse"', $album_js, $album_js) ;
        else
          echo ' style=" display:none" ';
        ?>
    >
      <h5><?php echo $album ; ?></h5>
    </button>
  </h5>
  <div 
  <?php if ($toplevel) 
              printf ('class="card-body collapse" id="%s-collapse"', $album_js) ;
            else
              echo 'class="card-body"' ;
      ?>
  >
    <h5 class="card-title">Released: <?php echo $year ; ?></h5>
    <img width="90%" class="cover" style="border-radius: 10px;box-shadow: 10px 10px 8px grey"
      <?php if ($cover != NULL) echo 'src="/media/' . $dir . '/'. $cover . '"'; ?> />
      <?php if ($cover != NULL) echo '<br><br>' ;?>
    <p class="card-text">
      <?php if ($description != NULL) echo $description ; ?>
    </p>

    <div class="list-group m-3">
      <!-- <a href="#" class="list-group-item list-group-item-action active">
        Track 1
      </a> -->
      <?php
        $active_ = false ;
        foreach ($tracks as $t) {
          if ($t == '.' || $t == '..')
            continue ;
          if (strpos ($t, ".mp3") == false && strpos ($t, ".ogg") == false)
            continue ;
          $active = ' ' ;
          // printf ('console.log ("%s")', $t) ;
          if (strpos (strtolower ($t), $song) !== false && $active_ === false) {
            $active = 'active text-white' ;
            $active_ = true ;
            printf ('
            <script>
              var song_id = "%s" ;
            </script>',  str_replace (' ', '_',$t)) ;
          }
          // print ($active) ;
          printf ('<script>%s.push ({url: "/media/%s/%s"}); </script>', $album_js, $dir, $t) ;
          echo (sprintf ('
            <div class="list-group-item list-group-item-action mflex justify-content-between %s">
              <a class="%s" href="javascript: play_track (\'%s\')" >%s</a>&nbsp&nbsp&nbsp&nbsp
              <div>
                <div class="d-flex">
                  <a href="javascript: play_track (\'%s\')" class="text-white btn btn-success fa fa-play"></a>
                  <a href="javascript: play_track (\'%s\', \'%s\', true)" class="text-white btn btn-primary fa fa-plus"></a>
                  <a download href="%s" class="text-white btn btn-info fa fa-download"></a>
                  <div id="%s"></div>
                </div>
              </div>
            </div>',
            $active, $active, $t, pathinfo(basename ($t), PATHINFO_FILENAME), $t, $t, str_replace (' ', '_',basename ($t)), $t, str_replace (' ', '_', basename ($t)))) ;
        }
        ?>
      
    </div>


    <a href="javascript: play_track (<?php echo $album_js ?>, '<?php echo $album_js ?>')" class="btn m-1 btn-success fa fa-play">&nbsp&nbspPlay Album</a>
    <a href="javascript: play_track (<?php echo $album_js ?>, '<?php echo $album_js ?>', true)"  class="m-1 btn btn-primary fa fa-plus">&nbsp&nbspEnqueue</a>
  </div>
</div>
<div  id="<?php echo $album_js ?>"></div>

<!-- container -->
<!-- </div> -->

<?php
  if (!$toplevel)
    require_once ("ui/footer.php") ;
?>

<script>
  if (song != null) {
    document.getElementById (song_id). scrollIntoView ()
    window.scrollBy (0, -30)
  }
</script>
