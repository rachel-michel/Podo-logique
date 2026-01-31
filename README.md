# Podo-logique

Créé par une orthopédiste, pour les orthopédistes. Cette application a pour objectif de simplifier le suivi patient et la génération d'un compte rendu prescripteur lors des bilans podologiques !

## 👩 Présentation

Je m'appelle Rachel MICHEL, je suis orthopédiste orthésiste (et anciennement développeur web).
En entrant dans le métier, je me suis rapidement aperçu que la réalisation d'un bilan podologique et d'un suivi pouvait devenir quelque chose de fastidieux sans un minimum d'organisation. 
Ne trouvant pas mon compte dans les logiciels existant, j'ai donc pris l'initiative de créer une application pour m'aider à réaliser les bilans podologique, le suivi de mes patients, et ainsi pouvoir génerer en quelques clics des bilans complets à envoyer aux prescripteurs !

## 🌍 Une app "Open source"

❓Open source ça veux dire quoi ? 
Que le code source peut être utilisé, modifié et partagé librement par quiconque.

❓Alors c'est gratuit ?
Oui oui, et ça le restera à vie. Si j'ai créé cette app, ce n'est ni pour la gloire, ni pour l'argent. C'est pour m'aider au quotidien. 
Vous pensez que ça peut vous aider vous aussi ? Alors testez là et faites-vous un avis, ça ne coûte rien !

## 📖 Documentation

La documentation complète de l'application est disponible [en cliquant sur ce lien](https://www.notion.so/Podo-logique-la-documentation-compl-te-2f459f2e574080e5988be4c84501503d?source=copy_link).
Vous pourrez ainsi voir comment télécharger l'app, la lancer et vous en servir. Amusez-vous bien !

# ⚠️ Mise en garde

## 💻 100% hors ligne

Dans cette application, vous êtes amené à renseigner des données de santé (antécédents, bilan podologique...) et permettant d'identifier un patient (nom, prénom, date de naissance...).

Dans le cas d'un site en ligne (qui utilise internet), ces données doivent être stockées sur un serveur dit HDS : Hébergeur de Données de Santées.
Dans le cas d'un site hors ligne (qui n'utilise pas internet), c'est votre propre ordinateur qui peut assumer le role de serveur.

Pour des raisons budgétaires (un serveur certifié HDS... ça a un coût) **Podo'logique est une application 100% hors ligne**

> Le serveur de l'application n'est pas en ligne : c'est votre ordinateur lui-même qui est le serveur.
> De ce fait, les données enregistrées dans l'application ne sont accessible que par vous et votre ordinateur.

❓Si Podo'logique est une app hors ligne, pourquoi s'ouvre t-elle dans mon navigateur internet ?

Parce qu'elle a été codé sur la base de langages de programmation web. Cependant, elle est belle et bien hors ligne.
Testez par vous même ! Coupez internet et utilisez l'application.
Le seul onglet dépendant d'internet (et donc qui ne fonctionnera plus sans internet) c'est celui de la documentation 😉 tout le reste de l'app est fonctionnel.

⚠️ **Que l'application soit hors ligne ne signifie pas pour autant qu'elle est inviolable.** ⚠️

Les données stockées dans le fichier app.db ne sont pas chiffrées. Autrement dit, si une personne mal intentionné accède à votre ordinateur par un moyen quelconque (accès physique, virus...), il pourrait très bien en récuperer les données !

Podo'logique est une application hors ligne autonome. Dès lors que vous la téléchargez sur votre ordinateur, vous en avez l'usage et la responsabilité.

**Je ne peux en aucun cas être responsable d'une fuite de donnée lié à un manque de sécurité sur votre propre ordinateur.**

> Vous et vous seul avez la responsabilité de vous assurer de la sécurité de votre ordinateur.
> Pour plus d'informatons : https://www.cybermalveillance.gouv.fr/tous-nos-contenus/bonnes-pratiques/10-mesures-essentielles-assurer-securite-numerique

# 🐞 Gestion des erreurs fréquentes

### Erreur au lancement "VCRUNTIME140.dll"

Pour corriger cette erreur, il suffit d'[installer les dernières version de Visual C++ Redistributable v14 en x86 ET x64](https://learn.microsoft.com/fr-fr/cpp/windows/latest-supported-vc-redist?view=msvc-170#latest-supported-redistributable-version).
