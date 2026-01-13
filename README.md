# Podo-logique
Créé par une orthopédiste, pour les orthopédistes. Cette application a pour vocation de simplifier la gestion des dossiers patient, la prise de note ainsi que la génération d'un compte rendu prescripteur lors des bilans podologiques !

Todo :
- optimisation (contrôler les requêtes en db et leur indispensabilité)
- les onglets du header (library et prescriber) doivent reload les onglet examination (pour library) et pdf parameter (pour prescriber)
- modifier la table des pdf parameter, elle ne doit pas contenir un prescriber_id mais les données fullname, address, mail, phoneNumber qui seront pré-rempli
- rendre le CR joli


### Erreur au lancement "VCRUNTIME140.dll"

Installer les dernières version de Visual C++ Redistributable v14 en x86 ET x64

https://learn.microsoft.com/fr-fr/cpp/windows/latest-supported-vc-redist?view=msvc-170#latest-supported-redistributable-version
