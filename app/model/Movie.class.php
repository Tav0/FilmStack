<?php
class Movie {
    private $apiHandler = null;
    private $movieData = null;
	private $movieId = null;
	private $error = false;


    public function __construct($id) {
		$movieId = $id;
        $this->apiHandler = new APIHandler();
        $this->movieData = $this->apiHandler->searchByMovieId($id);
		if($this->movieData == null)
			$this->error = true;
    }
    
	public function error() {
		return $this->error;
	}
	
	public function getId() {
		return $this->movieId;
	}
	
	//Gets the movie's title
    public function getTitle() {
        return $this->movieData['title'];
    }
    
	//Gets the movie's genres
    public function getGenres() {
        return $this->movieData['genres'];
    }
    
	//Gets the production companies
    public function getProductionCompanies() {
        return $this->movieData['production_companies'];
    }
    
	//Gets the production countries
    public function getProductionCountries() {
        return $this->movieData['production_countries'];
    }
    
	//Gets the movie's overview
    public function getOverview() {
        return $this->movieData['overview'];
    }
    
	//Gets the movie's poster url
    public function getPosterUrl() {
        $posterPath = $this->movieData['poster_path'];
        $posterUrl = $this->apiHandler->getImagesUrlPrefix() . $posterPath;
        return $posterUrl;
    }
    
	//Gets the movie's backdrop url
    public function getBackdropUrl() {
        $backdropPath = $this->movieData['backdrop_path'];
        $backdropUrl = $this->apiHandler->getImagesUrlPrefix() . $backdropPath;
        return$backdropUrl;
    }
    
	//Gets the movie's popularity
    public function getPopularity() {
        return $this->movieData['popularity'];
    }
    
	//Gets the movie's release date
    public function getReleaseDate() {
        return $this->movieData['release_date'];
    }
    
	//Gets the movie's revenue
    public function getRevenue() {
        return $this->movieData['revenue'];
    }
    
	//Gets the movie's runtime
    public function getRuntime() {
        return $this->movieData['runtime'];
    }
    
	//Gets the movie's tagline
    public function getTagline() {
        return $this->movieData['tagline'];
    }
}
