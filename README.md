🔄 RiKaZarai Sylius Product Duplication Plugin
Afficher l'image
Afficher l'image
Afficher l'image
Afficher l'image
Afficher l'image
Afficher l'image
Afficher l'image

Plugin Sylius professionnel développé par RiKaZarai 👨‍💻
Dupliquez vos produits en un clic ! Gain de temps garanti pour vos intégrations e-commerce.

✨ Fonctionnalités

🔄 Duplication individuelle : Bouton "Dupliquer" sur chaque produit
📦 Duplication en lot : Sélection multiple et duplication de masse
🔒 Sécurité CSRF : Protection contre les attaques CSRF
🌐 Multi-langue : Support complet FR/EN (extensible)
⚡ Performance optimisée : Duplication efficace avec gestion des relations
🎯 Interface intuitive : Intégration native dans l'interface admin Sylius
✅ Codes uniques : Génération automatique de codes et slugs uniques
🔧 Configurable : Options pour personnaliser la duplication
🛡️ Résistant aux mises à jour : Utilise les Twig Hooks Sylius 2.x

📋 Prérequis

PHP 8.3 ou supérieur
Symfony 7.3 ou supérieur
Sylius 2.0 ou supérieur

🚀 Installation rapide
bash# Installation du plugin
composer require rikazarai/sylius-product-duplication-plugin

# ✅ Avec Symfony Flex, l'activation est automatique !
# Le plugin sera automatiquement enregistré dans config/bundles.php

# Vider le cache
php bin/console cache:clear
⚙️ Configuration
Le plugin peut être configuré via config/packages/rikazarai_sylius_product_duplication.yaml :
yamlrikazarai_sylius_product_duplication:
    enabled: true                    # Activer/désactiver le plugin
    copy_images: true               # Copier les images des produits
    copy_associations: true         # Copier les associations de produits
    duplicate_suffix: ' (Copie)'    # Suffixe ajouté aux noms des produits dupliqués
🎯 Utilisation
Duplication individuelle

Allez dans Catalogue > Produits dans l'admin Sylius
Cliquez sur le bouton "Dupliquer" (icône copie bleue) à côté du produit désiré
Confirmez l'action
Le nouveau produit dupliqué s'ouvre automatiquement pour édition

Duplication en lot

Allez dans Catalogue > Produits dans l'admin Sylius
Cochez les cases des produits à dupliquer
Cliquez sur "Dupliquer les produits sélectionnés" (bouton orange en haut)
Confirmez l'action
Tous les produits sélectionnés sont dupliqués

🔧 Ce qui est dupliqué
✅ Données complètement copiées

✅ Informations de base : Statut, catégories de taxe/expédition
✅ Traductions : Noms, slugs, descriptions, méta-données SEO
✅ Variantes complètes : Options, prix, dimensions, stock
✅ Prix multi-canaux : Prix de base, prix originaux, prix minimum
✅ Relations : Canaux, taxons, attributs, options produit
✅ Associations : Produits liés, up-sell, cross-sell
✅ Images et médias : Toutes les images produits
✅ Configuration avancée : Toutes les propriétés métier

🔄 Données uniques générées

🆔 Codes produits : ORIGINAL-copy-1, ORIGINAL-copy-2, etc.
🔗 Slugs SEO : original-slug-copy-1, original-slug-copy-2, etc.
📝 Noms affichés : Nom original (Copie)

🛡️ Sécurité

Protection CSRF : Tous les formulaires incluent des tokens CSRF
Permissions : Vérification des droits d'administration
Validation : Validation des données d'entrée
Codes uniques : Vérification d'unicité des codes et slugs

🧪 Tests
bash# Tests unitaires
./vendor/bin/phpunit tests/Unit

# Tests fonctionnels
./vendor/bin/phpunit tests/Functional

# Tous les tests
./vendor/bin/phpunit
👨‍💻 Auteur
RiKaZarai - Développeur Full Stack Symfony/Sylius Expert

🐙 GitHub: @RiKaZarai

🤝 Contribution
Les contributions sont les bienvenues !

Fork le projet sur GitHub
Créez votre branche (git checkout -b feature/AmazingFeature)
Committez (git commit -m 'Add some AmazingFeature')
Pushez (git push origin feature/AmazingFeature)
Ouvrez une Pull Request

📄 Licence
Ce plugin est distribué sous licence MIT. Voir le fichier LICENSE pour plus de détails.

<div align="center">
⭐ Si ce plugin vous aide, n'hésitez pas à lui donner une étoile ! ⭐
Développé avec ❤️ par RiKaZarai pour la communauté Sylius
</div>
