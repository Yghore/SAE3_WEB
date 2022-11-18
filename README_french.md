# SAE3_WEB
SAE DU WEB

## Membres   
Régis Vavasseur  
Guillaume Wissle   
Mehdi Allali 
Elian Guiffault  

## Lien

Git : https://github.com/Yghore/SAE3_WEB/  
Webetu : https://webetu.iutnc.univ-lorraine.fr/~wissle1u/

## Setup (Mise en place)

Mise en place de la base de donnée : 

- Fichier de mise en place par le sujet
- Le fichier ``db.sql``
- Mettre en place le fichier db.config.ini comme ceci : 
```ini
host=localhost
dataBase=sae
driver=mysql
username=USERNAMEHERE
password=PASSWORDHERE
```


## Fonctionnalitées de base & étendu

1. Identification/Authentification – Formulaire de login,

    ✅


2. Inscription sur la plateforme

    ✅

3. Affichage du catalogue de séries

    ✅

4. Affichage détaillé d’une série et de la liste de ses épisodes

    ✅


5. Affichage/visionnage d’un épisode d’une série

    ✅

6. Ajout d’une série dans la liste de préférence d’un utilisateur

    ✅

7. Page d’accueil d’un utilisateur : afficher ses séries préférées

    ✅

8. Lors du visionnage d’un épisode, ajouter automatiquement la série à la liste " en cours "

    ✅

9.  Lors du visionnage d’un épisode d’une série, noter et commenter la série

    ✅   
    __Bugfix__  : Si l'utilisateur à déja commenté une série, le rediriger vers une page d'erreur (: Vous avez déjà noté cette série)


10. Lors de l’affichage d’une série, indiquer sa note moyenne et donner accès aux commentaires

    ✅    
    __Bugfix__ : Note moyenne manquante, cependant, une méthode dans le modèle 'Serie' existe et est fonctionnelle, 

11. Activation de compte

    ✅  
    __Piste d'amélioration optionelle__: Si l'utilisateur à perdu le token ou si le token à expirer alors r'envoyer en cas d'essaye de connexion (ou le regénerer à la demande en cliquant sur un bouton)

17. gestion du profil de l’utilisateur : ajouter des informations (nom, prénom, genre
préféré ...)

    ✅   
    __Bugfix__ : Si l'utilisateur n'a pas ajouté d'age, il est considéré comme majeur (l'inverse serait surement mieux)

12. Recherche dans le catalogue par mots clés

    ✅   
    __Piste d'amélioration optionelle__ : Supression des mots de liaison dans la recherche (a, ou, ils, etc...)

13. Tri dans le catalogue

    ✅

14. filtrage du catalogue par genre, par public


    ❌
    __Non terminée :__ Le filtrage par public à été commencé (avec le système d'age) cependant le filtre n'est pas appliqué

15. Gestion de la liste de préférence : retrait

    ✅

16. Gestion de la liste « déjà visionnées »

    ✅

17. accès direct à l’épisode à visionner lorsque l’on visualise une série qui est dans la
liste « en cours »

    ✅

19. Tri dans le catalogue selon la note moyenne

    ❌
    
20. mot de passe oublié

    ✅


## Fonctionnalité supplémantaire : 

21. Système de déconnexion (vide la session de l'utilisateur)  

    ✅


22. Bouton "Retour arrière" 

    ✅  