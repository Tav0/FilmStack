<div class="container" id="Upcoming">
  <div class="row">
    <div class="col-xs-12">
      <p class="genre_title">Upcoming</p>
      <div id="carousel-example-generic" class="carousel" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
          <div class="item active">
            <a href="movie/<?= $movieList[0]['id'] ?>">
							<img src="<?= $apiHandler->getImagesUrlPrefix().$movieList[0]['poster_path'] ?>" 
									alt="<?= $movieList[0]['title'] ?>"></a>
          </div>
          <?php
								for ($x = 1; $x < 10; $x++) {
							?>
            <div class="item">
              <a href="movie/<?= $movieList[$x]['id'] ?>">
										<img src="<?= $apiHandler->getImagesUrlPrefix().$movieList[$x]['poster_path'] ?>"
											alt="<?= $movieList[$x]['title'] ?>"></a>
            </div>
            <?php
								};
							?>

              <!-- Controls -->
              <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"
				aria-hidden="true"></span><span class="sr-only">Previous</span></a>
              <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span></a>
        </div>
      </div>
    </div>
  </div>
</div>
