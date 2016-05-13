# Concept
Tired of missing the date of an upcoming movie or forgetting which upcoming
movie you were waiting for a whole year? 

Used themoviedb.org API to start developing a favorite upcoming movies watch
list for users to track their upcoming movies with the ability to get notifications 
using Google Calendar so you can get the notification on your phone.

Focus of this project is to get familiar with PHP, APACHE and MYSQL.

I will be transferring the front-end to React.js

# Design Implementation
## Main page
	- Nav bar
	- User's dropdown
	- Not logged in
		- user, pass, forgot pass, create account
		- logged in
		- profile and logout
	- Search bar, below nav bar (bigger than other pages)
	- Title
	- Upcoming movies and genre sub-title
		- A carousel list of movies
		- Hover for rating
		- Hover for brief sypnosis

## Search movie results
	- List of results
		- title
		- year
		- rating
		- brief sypnosis by hover over
		- add button
		- 2 options to add to watch or watched list

## Movie page
	- Nav bar
		- User's dropdown login
		- Search bar on nav bar
	- Title of movie
	- Movie poster
	- Summary
	- Add to list
	- Year of movie
	- Actors

## User's list
	-  Nav bar
		-  User's dropdown login
		- Search bar on nav bar
	- Main filters
		- All movies 
		- To watch movies
		- Watched movies
	- Delete button of a movie
	- Watched/Unwatched button of movie

## Create account page
	- Name project
	- Create username
	- Create email
	- Login password
	- Retype password
	- Register button

## Forgot password page
	- Email input
	- Show password

# Functional implementation
## Main page
	- Nav bar
		- Login functionality
	- Search functionality through API
	- Pull list of movies from API

## Search movie results
	- Functionality to add movie to list, API calls
	- Add movie to DB

## User's list
	- Filtering for all, watch, watched
	- Add, delete, watched functionality for db

## Create account page
	- Add user to DB

## Forgot pass page
	- Pull pas from db

## Movie page
	- Calls to API
	- Add list to DB

## User's account page
