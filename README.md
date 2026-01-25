# Podo-logique

Créé par une orthopédiste, pour les orthopédistes. Cette application a pour vocation de simplifier la gestion des dossiers patient, la prise de note ainsi que la génération d'un compte rendu prescripteur lors des bilans podologiques !

### Comment ajouter son logo dans le compte rendu ?

Il suffit d'ajouter votre logo au format png dans le dossier : Podo-logique/public/assets
Veillez à bien renommer votre fichier : logo.png

## Gestion des erreurs

### Erreur au lancement "VCRUNTIME140.dll"

Installer les dernières version de Visual C++ Redistributable v14 en x86 ET x64

https://learn.microsoft.com/fr-fr/cpp/windows/latest-supported-vc-redist?view=msvc-170#latest-supported-redistributable-version

# TODO

- Trouver pourquoi l'impression pdf génère des pages vides à la suite du document
- Trouver comment cacher la console au démarrage de l'app / comment forcer l'arrêt à la fermeture de l'onglet
- Bug sur la modif d'un prescripteur

Rappel SMS

- Ajouter un onglet "Rappel patient" dans lequel s'affiche les patients (nom, prénom, téléphone) dont le dernier bilan podo a été fait il y a minimum 11 mois
