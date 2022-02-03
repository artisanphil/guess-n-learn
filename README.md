## Guess N Learn

This games is based on Guess Who, but for language learning.

There will be 3 quiz modes:

-   multiple choice (random from all questions)
-   fill in the blank
-   jumbled sentence

Ideas for guessing game alternatives:

-   What is this person doing?
-   What food is it?
-   What is the time?
-   What animal is it? https://shopping.rspb.org.uk/games/rspb-guess-who-game.html

## Technologies:

Laravel API

Nginx

Vue JS with Typescript

Tailwind

Deploy with Docker

## Installation

docker run --rm -v $(pwd):/app composer install

Copy env.example to .env

Start Docker and run `docker-compose up -d` then go to http://localhost:8000/

## Flow

Set the language the user wants to learn, for example en_uk
[GET] api/learn-language/{locale}

Return all available objects and if they are still available (active true/false)
[GET] api/objects

User selects an object:
[POST] api/select?selection={objectname}
Computer randomly selects an object

Computer makes a guess:
[GET] api/computer-guess

User answers if computer guess is correct:
[POST] api/computer-guess?choice={attribute}&correct=1
Computer returns message if correct or not, returns all objects
that match

Display all available attributes (not yet selected) for user:
[GET] api/remaining-attributes

User makes a guess:
[POST] api/user-guess?choice={attribute}
one can add attribute questiontype testing purposes

Sentence options get presented based on attribute choice
[GET] api/user-guess

User posts sentence
[POST]
api/user-guess?choice={attribute}&sentence={sentence}
All matching objects get returned

Back to computer makes a guess until only one object left for either user or computer.
