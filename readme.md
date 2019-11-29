Pour récupérer ce dossier, tu devras lancer les commande (attention, il faut que tu sois dans le dossier du projet Symfony) :

    composer install
    yarn install
    yarn encore dev
    
Créer le fichier .env.local

    doctrine:database:create
    doctrine:migration:migrate
    symfony server:start 


https://www.loom.com/share/e0db8866e649475195f620f2bde29494