<div class="container" id="<?= $name_genre ?>">
	<div class="row">
		<div class="col-xs-12">
			<p class="genre_title">
				<?= $name_genre ?>
			</p>
			<div id="carousel-<?= $name_genre ?>" class="carousel" data-ride="carousel">
				<!-- Indicators -->
				<!-- Wrapper for slides -->
				<div class="carousel-inner" role="listbox">
					<div class="item active">
						<a href="movie/<?= $genreList[0]['id'] ?>">
							<img src="<?= $apiHandler->getImagesUrlPrefix().$genreList[0]['poster_path'] ?>" 
							alt="<?= $genreList[0]['title'] ?>">
						</a>
					</div>
					<?php
						for ($x = 1; $x < 3; $x++) {
					?>
					<div class="item">
						<a href="movie/<?= $genreList[$x]['id'] ?>">
							<img src="<?= $apiHandler->getImagesUrlPrefix().$genreList[$x]['poster_path'] ?>"
							alt="<?= $genreList[$x]['title'] ?>">
						</a>
					</div>
					<?php
						};
					?>

					<!-- Controls -->
					<a class="left carousel-control" href="#carousel-<?= $name_genre?>" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"
						aria-hidden="true"></span><span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-<?=
						$name_genre ?>" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
