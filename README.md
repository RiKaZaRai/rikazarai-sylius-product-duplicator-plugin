# 🔄 RiKaZarai Sylius Product Duplication Plugin

[![Packagist Version](https://img.shields.io/packagist/v/rikazarai/sylius-product-duplication-plugin.svg)](https://packagist.org/packages/rikazarai/sylius-product-duplication-plugin)
[![Total Downloads](https://img.shields.io/packagist/dt/rikazarai/sylius-product-duplication-plugin.svg)](https://packagist.org/packages/rikazarai/sylius-product-duplication-plugin)
[![GitHub Stars](https://img.shields.io/github/stars/RiKaZarai/sylius-product-duplication-plugin.svg)](https://github.com/RiKaZarai/sylius-product-duplication-plugin)
![Sylius](https://img.shields.io/badge/Sylius-2.0+-green.svg)
![Symfony](https://img.shields.io/badge/Symfony-7.3+-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.3+-purple.svg)
![License](https://img.shields.io/badge/License-MIT-yellow.svg)

Plugin Sylius qui permet de **dupliquer des produits** individuellement ou en lot depuis le panel d'administration.

## ✨ Fonctionnalités

- 🔄 **Duplication individuelle** avec bouton dédié sur chaque produit
- 📦 **Duplication en lot** avec sélection multiple
- 🔒 **Sécurité CSRF** et vérification des permissions
- 🌐 **Multi-langue** (Français/Anglais)
- ⚡ **Performance optimisée** pour gros catalogues
- ✅ **Codes uniques** générés automatiquement
- 🎯 **Interface native** intégrée à l'admin Sylius

## 🚀 Installation

### Avec Composer

```bash
composer require rikazarai/sylius-product-duplication-plugin
```

### Configuration manuelle (si Flex non utilisé)

1. **Activer le bundle** dans `config/bundles.php` :
```php
RiKaZarai\SyliusProductDuplicationPlugin\RiKaZaraiSyliusProductDuplicationPlugin::class => ['all' => true],
```

2. **Importer les routes** dans `config/routes/rikazarai_product_duplication.yaml` :
```yaml
rikazarai_admin:
    resource: "@RiKaZaraiSyliusProductDuplicationPlugin/Resources/config/routing/admin.yaml"
    prefix: /%sylius_admin.path_name%  
```

3. **Importer la configuration** dans `config/packages/rikazarai_sylius_product_duplication.yaml` :
```yaml
imports:
    - { resource: "@RiKaZaraiSyliusProductDuplicationPlugin/Resources/config/config.yaml" }
    - { resource: "@RiKaZaraiSyliusProductDuplicationPlugin/Resources/config/sylius_twig_hooks.yaml" }
```

4. **Vider le cache** :
```bash
php bin/console cache:clear
```

## 🎯 Utilisation

### Duplication individuelle
1. Allez dans **Catalogue > Produits**
2. Cliquez sur le bouton bleu **"Dupliquer"** 
3. Le produit dupliqué s'ouvre pour édition

### Duplication en lot
1. Sélectionnez les produits à dupliquer (cases à cocher)
2. Cliquez sur **"Dupliquer les produits sélectionnés"**
3. Confirmez l'action

## ⚙️ Configuration

```yaml
# config/packages/rikazarai_sylius_product_duplication.yaml
rikazarai_sylius_product_duplication:
    enabled: true                    # Activer/désactiver
    copy_images: true               # Copier les images
    copy_associations: true         # Copier les associations
    duplicate_suffix: ' (Copie)'    # Suffixe des noms
```

## 🔧 Données dupliquées

### ✅ Complètement copiées
- Informations de base (statut, catégories)
- Traductions (noms, descriptions, SEO)
- Variantes avec prix et options
- Relations (canaux, taxons, attributs)
- Associations et images

### 🔄 Générées automatiquement
- **Codes** : `PRODUIT-copy-1`, `PRODUIT-copy-2`...
- **Slugs** : `slug-copy-1`, `slug-copy-2`...
- **Noms** : `Nom original (Copie)`

## 📋 Prérequis

- PHP 8.3+
- Symfony 7.3+
- Sylius 2.1+

## 🧪 Tests

```bash
./vendor/bin/phpunit              # Tous les tests
./vendor/bin/phpunit tests/Unit   # Tests unitaires
```

## 👨‍💻 Auteur

**RiKaZarai** - [@RiKaZarai](https://github.com/RiKaZarai)

## 🤝 Contribution

1. Fork le projet
2. Créez votre branche (`git checkout -b feature/ma-fonctionnalite`)
3. Commit (`git commit -m 'Ajoute ma fonctionnalité'`)
4. Push (`git push origin feature/ma-fonctionnalite`) 
5. Ouvrez une Pull Request

## 📄 Licence

MIT License - voir le fichier [LICENSE](LICENSE)

## 🙏 Support

- **Issues** : [GitHub Issues](https://github.com/RiKaZarai/sylius-product-duplication-plugin/issues)
- **Documentation** : [Wiki](https://github.com/RiKaZarai/sylius-product-duplication-plugin/wiki)

---

<div align="center">

**Développé par [RiKaZarai](https://github.com/RiKaZarai) pour la communauté Sylius**

⭐ **Si ce plugin vous aide, donnez-lui une étoile !** ⭐

</div>
