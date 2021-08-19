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

Start Docker and run `./vendor/bin/sail up` then go to http://localhost

## Flow

Display all available objects:
[GET] api/index

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

Sentence options get presented based on attribute choice
[GET] api/user-guess

User posts sentence
[POST]
api/user-guess?choice={attribute}&sentence={sentence}
All matching objects get returned

Back to computer makes a guess until only one object left for either user or computer.
