<?php

/*
 Class for interacting with The Movide Database API
 */
class APIHandler {

	private $api_key = "403fe5a1d4b027392f389ef579ce991c";
	private $base_api_url = "http://api.themoviedb.org/3/";
	private $images_url_prefix = '';
	
    /*
     Set the images_url_prefix field
     */
	public function __construct() {
        // set $this->images_url_prefix
        $config = $this->getConfig();
        $imagesConfig = $config['images'];
        $imagesBaseUrl = $imagesConfig['base_url'];
        $this->images_url_prefix = $imagesBaseUrl.'original';
	}
    
    /*
        Return base api url field
    */
    public function getBaseApiUrl() {
        return $this->base_api_url;
    }
    
    public function getImagesUrlPrefix() {
        return $this->images_url_prefix;
    }

	/*
		Returns the list of results for movies with the specified movie name
	 */
	public function searchByName($movieName, $pageNumber=null) {
        $pageQueryParam = '';
        if ($pageNumber !== null) {
            $pageQueryParam = "&page={$pageNumber}";
        }


		$encoded_movieName = urlencode($movieName);
		$url = $this->base_api_url . "search/movie?query=" . $encoded_movieName . $pageQueryParam . "&api_key=" . $this->api_key;
		return $this->makeRequest($url);
	}

	/*
		Returns the list of results for movies with the specified genre name
	 */
	public function searchByGenre($id, $pageNumber=null) {
        $pageQueryParam = '';
        if ($pageNumber !== null) {
            $pageQueryParam = "&page={$pageNumber}";
        }


		$url = $this->base_api_url . "genre/" . $id . "/movies?api_key=" . $this->api_key . $pageQueryParam;
		return $this->makeRequest($url);
	}

	/*
		Return the result for the movie with the specified movie id
	 */
	public function searchByMovieID($movieID) {
		$url = $this->base_api_url . "movie/" . $movieID . "?api_key=" . $this->api_key;
		return $this->makeRequest($url);
	}

	/*
		Returns the list of most popular movies on the TheMovieDatabase
	 */
	public function searchMostPopular() {
		$url = $this->base_api_url . "movie/popular?api_key=" . $this->api_key;
		return $this->makeRequest($url);
	}

	/*
		Returns the list of recently released movies
	 */
	public function searchRecentlyReleased() {
		$url = $this->base_api_url . "movie/now_playing?api_key=" . $this->api_key;
		return $this->makeRequest($url);
	}

	/*
		Returns the list of upcoming movies
	 */
	public function searchUpComing() {
		$url = $this->base_api_url . "movie/upcoming?api_key=" . $this->api_key;
		return $this->makeRequest($url);
	}

	/*
		Returns the list of movies from the specified genre (using the genre id)
	 */
	public function searchGenre($genreID) {
		$url = $this->base_api_url . "genre/" . $genreID . "/movies?api_key=" . $this->api_key;
		return $this->makeRequest($url);
	}

	/*
		Returns the list of genres from TheMovieDatabase along with their id values
	 */
	public function getGenres() {
		$url = $this->base_api_url . "genre/movie/list?api_key=" . $this->api_key;
		return $this->makeRequest($url);
	}
	
    /*
        Return config
    */
    public function getConfig() {
		$url = $this->base_api_url . "configuration?api_key=" . $this->api_key;
		return $this->makeRequest($url);
    }

	/*
		Returns the json response from a get request to TheMovieDatabase
	 */
	public function makeRequest($url) {
		try {
			$json = json_decode(file_get_contents($url), true);
		}
		catch(Exception $e) {
			return null;
		}
		return $json;
	}

}
