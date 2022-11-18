# SAE3_WEB
SAE DU WEB

## Members   
Régis Vavasseur  
Guillaume Wissle   
Mehdi Allali   
Elian Guiffault  

## Links

Source code : https://github.com/Yghore/SAE3_WEB/  
Application host : https://webetu.iutnc.univ-lorraine.fr/~wissle1u/

## Setup

Setup of the Database : 

- Get the first file from the subject on arche (Web development's section)
- Get the second file from the github (db.sql)
- Import these two files in your database (in this order)
- Create db.config.ini at the root of the project (at the same location as the index.php), and edit it this way : 
```ini
host=localhost ; Host of server database
dataBase=sae ; Table of project
driver=mysql ; Driver use mysql or mariadb
username=USERNAMEHERE ; Your username (create a specifically account for the project)
password=PASSWORDHERE ; Password of account
```


## Basic and extended functionalities

1. Identitifcation/Authentication – Sign in form

    ✅


2. Registration functionality

    ✅

3. Series catalog display

    ✅

4. Detailed display of a TV Show and of the list of its episodes.

    ✅


5. Display and watch of a series episode

    ✅

6. Adding a series to a user's preference list


    ✅

7. A user's home page : display his favorite series

    ✅

8. When watching an episode, automatically add the series to the "current" list

    ✅

9.  When watching an episode of a series, rate and comment on the series

    ✅   
    __Bugfix__  : If user a has already commented the series, application print a error (Solution : redirect the user into a serie's page).


10. When viewing a series, indicate its average rating and provide access to comments

    ✅    
    __Bugfix__ : Average not missing, however, a method exist in serie model and work but not used, 

11. Account activation

    ✅  
    __Optional upgrade track__: SIf the user has lost the token or if the token has expired, then send it again when trying to connect (or regenerate it on demand by clicking on a button)

12. user profile management : add information (name, first name,favorite genre ...)

    ✅   
    __Bugfix__ : If the user has not added an age, he is considered to be of legal age (the reverse would surely be better)

12. Search the catalog by keywords

    ✅   
    __Piste d'amélioration optionelle__ : Removal of linking words in the search

13. Sorting ine the catalog

    ✅

14. Catalog filtering by genre, by audience


    ❌
    __Not finished :__ Filtering by public has been started (with the age system) however the filter is not applied

15. management of the preference list : remove

    ✅

16. management of the "already watched" list

    ✅

17. Direct access to the episode to view when viewing a series that is in the
“in progress” list

    ✅

19. Sort the catalog by average mark

    ❌
    
20. Forgot password

    ✅


## Additional functionality : 

21. Log out system (empties the user's session)  

    ✅


22. Previous page button 

    ✅  