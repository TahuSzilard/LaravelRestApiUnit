| URL          | HTTP method | Auth | JSON Response     |
| ------------ | ----------- | ---- | ----------------- |
| /users/login | POST        |      | user's token      |
| /users       | GET         | Y    | all users         |
| /films       | GET         |      | all films         |
| /films       | POST        | Y    | new film added    |
| /films       | PATCH       | Y    | edited film       |
| /films       | DELETE      | Y    | id                |
| /series      | GET         |      | all series        |
| /series      | POST        | Y    | new serie added   |
| /series      | PATCH       | Y    | edited serie      |
| /series      | DELETE      | Y    | id                |
| /actors      | GET         |      | all actors        |
| /actors      | POST        | Y    | new actor added   |
| /actors      | PATCH       | Y    | edited actor      |
| /actors      | DELETE      | Y    | id                |
| /directors   | GET         |      | all directors     |
| /directors   | POST        | Y    | new director added|
| /directors   | PATCH       | Y    | edited director   |
| /directors   | DELETE      | Y    | id                |

| /directors/director/films| GET         |      | all films from directors    |
| /actors/actor/films   | GET         |      | all films from actors     |
| /films/film/actors   | GET         |      | all actors from films     |
| /films/film/actors   | POST        | Y    | new actor to film|
| /films/film/actors/actor   | PATCH       | Y    | edited actor to film   |
| /films/film/actors/actor   | DELETE      | Y    | delete actor id from film                |
| /films/film/directors   | GET         |      | all directors from films    |
| /films/film/directors   | POST        | Y    | new director added to films|
| /films/film/directors/director   | PATCH       | Y    | edited director to films   |
| /films/film/directors/director   | DELETE      | Y    | delete director from films  |
 
 
 
 