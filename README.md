# Podo-logique

Créé par une orthopédiste, pour les orthopédistes. Cette application a pour objectif de simplifier le suivi patient et la génération d'un compte rendu prescripteur lors des bilans podologiques !

## Documentation

La documentation complète de l'application est disponible sur la page notion : https://glimmer-recorder-996.notion.site/ebd//2f459f2e574080e5988be4c84501503d

## Une app hors ligne

Dans cette application, vous êtes amené à renseigner des données de santé (antécédents, bilan podologique...) et permettant d'identifier un patient (nom, prénom, date de naissance...).

Dans le cas d'un site en ligne (qui utilise internet), ces données doivent être stockées sur un serveur dit HDS : Hébergeur de Données de Santées.
Dans le cas d'un site hors ligne (qui n'utilise pas internet), c'est votre propre ordinateur qui peut assumer le role de serveur.

Pour des raisons budgétaires (un serveur certifié HDS... ça a un coût) **Podo'logique est une application 99.9% hors ligne**
_(le 0.1% restant est représenté par la documentation que j'ai rédigée et importée depuis une page Notion 😂)_.

> Le serveur de l'application n'est pas en ligne : c'est votre ordinateur lui-même.
> De ce fait, les données enregistrées dans l'application ne sont accessible que par vous et votre ordinateur.

**Si Podo'logique est une app hors ligne, pourquoi s'ouvre t-elle dans mon navigateur internet ?**

Parce qu'elle a été codé sur la base de langages de programmation web. Cependant, elle est belle et bien hors ligne.
Testez par vous même ! Coupez internet et utilisez l'application.
Le seul onglet dépendant d'internet (et donc qui ne fonctionnera plus sans internet) c'est celui de la documentation 😉 tout le reste de l'app est fonctionnel.

# Mise en garde

**Que l'application soit hors ligne ne signifie pas pour autant qu'elle est inviolable.**

Les données stockées dans le fichier app.db ne sont pas chiffrées. Autrement dit, si une personne mal intentionné accède à votre ordinateur par un moyen quelconque (accès physique, virus...), il pourrait très bien en récuperer les données !

Podo'logique est une application hors ligne autonome. Dès lors que vous la téléchargez sur votre ordinateur, vous en avez l'usage et la responsabilité.

**Je ne peux en aucun cas être responsable d'une fuite de donnée lié à un manque de sécurité sur votre propre ordinateur.**

> Vous et vous seul avez la responsabilité de vous assurer de la sécurité de votre ordinateur.
> Pour plus d'informatons : https://www.cybermalveillance.gouv.fr/tous-nos-contenus/bonnes-pratiques/10-mesures-essentielles-assurer-securite-numerique

# Gestion des erreurs fréquentes

### Erreur au lancement "VCRUNTIME140.dll"

Pour corriger cette erreur, il suffit d'installer les dernières version de Visual C++ Redistributable v14 en x86 ET x64

https://learn.microsoft.com/fr-fr/cpp/windows/latest-supported-vc-redist?view=msvc-170#latest-supported-redistributable-version
