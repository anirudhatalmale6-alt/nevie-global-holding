# NEVIE-GLOBAL SAS — Jalon 1 (démonstration)

Thème sur mesure et modules CMS pour le site holding `nevie-global.fr`, conformes au
**Dossier complet V3**. Ce dépôt contient le livrable du **Jalon 1** : la Page 1 (Accueil)
et la Page 5 (Nos Entreprises / Participations), alimentées par le CMS.

---

## Ce qui est démontré ici

| Exigence du CDC | Où c'est traité | Vérifié |
|---|---|---|
| Charte : `#0D0B08`, `#C8961E` / `#D4A520`, `#0A6CFF`, Poppins, emblème « N » | `themes/nevie-global/assets/css/nevie.css` | captures `apercus/` |
| Page 1 — blocs imposés, interdits respectés | `themes/nevie-global/front-page.php` | `apercus/accueil-*.png` |
| Page 5 — Pôle → Entreprise → Statut → Fiche → Lien externe | `themes/nevie-global/page-nos-entreprises.php` | `apercus/nos-entreprises-*.png` |
| Fiche LIMITED : renvoi externe uniquement (correction V3) | champ « Présentation longue » laissé vide → pas de fiche détaillée | `apercus/nos-entreprises-1.png` |
| Les 13 champs de la Partie 5 | `mu-plugins/ng-participations.php` | métaboîte d'administration |
| Matrice des 4 rôles (Partie 14) | `mu-plugins/ng-roles.php` | `recette/test-wf17.php` |
| WF-17 — validation Administrateur obligatoire | `NG_Roles::forcer_en_attente()` | `recette/test-wf17.php` |
| WF-04 / WF-15 — journal d'audit | `NG_Roles`, `NG_Participations::sauvegarder()` | `recette/test-wf17.php` |
| Lighthouse ≥ 90 ×4 | aucune ressource externe, polices sous-ensemble | `recette/lighthouse-*.html` |
| WCAG 2.2 AA | contrastes, focus visible, ordre de titres, cible d'évitement | Lighthouse accessibilité 100 |
| RGPD — aucun traceur avant consentement | `themes/nevie-global/assets/js/nevie.js` | `apercus/rgpd-banniere.png` |

## Résultats mesurés

Lighthouse (préréglage bureau, Chromium 1200) :

| Page | Performance | Accessibilité | Bonnes pratiques | SEO |
|---|---|---|---|---|
| Accueil | **100** | **100** | **100** | **100** |
| Nos Entreprises | **100** | **100** | **100** | **100** |

LCP 0,4 s · CLS 0,05 · TBT 0 ms — l'objectif du CDC (≥ 90, LCP < 2,5 s, CLS < 0,1) est tenu
avec une marge confortable.

Recette des rôles et des workflows : **10 tests sur 10 réussis** (`php recette/test-wf17.php`).

```
PASS  WF-17 — la publication par l'Éditeur retombe en attente
PASS  Éditeur — ne détient pas publish_ng_participations
PASS  Éditeur — détient bien edit_ng_participations
PASS  Éditeur — ne détient pas delete_ng_participations
PASS  Responsable acquisitions — accède aux dossiers
PASS  Responsable acquisitions — aucun droit d'édition du site
PASS  Freelance développeur — aucun accès aux dossiers de cession
PASS  WF-17 — l'Administrateur publie le contenu soumis
PASS  WF-15 — la publication est journalisée
PASS  WF-04 — le changement de statut est journalisé
```

---

## Un point de charte à arbitrer

Le doré de la charte `#C8961E` n'offre que **2,5:1** de contraste sur fond clair, là où le
WCAG 2.2 AA exige 4,5:1 pour un texte courant. La charte et l'exigence d'accessibilité,
toutes deux au CDC, entrent donc en conflit sur un point précis.

Arbitrage retenu, à confirmer par NEVIE-GLOBAL SAS :

- sur fond noir, le doré de charte est employé **tel quel** (7,3:1) ;
- pour les petits textes sur fond clair, une déclinaison plus sombre de la même teinte,
  `#8A6410` (5,1:1), est utilisée ;
- le bleu `#0A6CFF` suit la même logique : `#0B5ED7` (5,6:1) pour les liens sur fond clair.

Rien n'est inventé hors charte : ce sont des déclinaisons des couleurs fournies. Si vous
préférez conserver `#C8961E` partout, il faudra assumer un écart documenté au WCAG 2.2 AA —
c'est votre décision, pas la mienne.

---

## Installation

```bash
# 1. Déposer les fichiers
cp -r wp-content/themes/nevie-global   /chemin/wordpress/wp-content/themes/
cp -r wp-content/mu-plugins/*.php      /chemin/wordpress/wp-content/mu-plugins/

# 2. Activer le thème (Apparence → Thèmes → NEVIE-GLOBAL)
# 3. Créer la page « Nos Entreprises » et lui appliquer le modèle
#    « Page 5 — Nos Entreprises / Participations »
# 4. Réenregistrer les permaliens (Réglages → Permaliens)
```

Les modules `mu-plugins` s'activent seuls : la matrice des rôles et le type de contenu
« Participation » sont en place dès le dépôt des fichiers.

## Ajouter une entreprise sans toucher au code

`Participations → Ajouter` : le nom est le titre, les 12 autres champs du CDC sont dans la
métaboîte « Fiche entreprise ». Le pôle est une taxonomie ; son ordre d'affichage se règle
par le champ `ng_ordre` du terme. Décocher « Statut actif/inactif » retire la fiche du site
public sans la supprimer.

Un changement de statut est **toujours** journalisé (utilisateur, date, ancienne et nouvelle
valeur), et aucun passage automatique vers « Détenue » n'existe dans le code.

## Périmètre de ce jalon

Livré : Pages 1 et 5, CMS participations, matrice des rôles, WF-17, journal d'audit
WF-04/WF-15, bannière cookies, socle de performance et d'accessibilité.

Non livré à ce stade — relève des jalons 2 à 5 : les 8 autres pages, les deux formulaires
et l'intégration n8n, l'identifiant de dossier `NG-AAAA-NNNNNN`, le stockage privé des
pièces jointes, la recette documentée des 21 workflows, la mise en production.

## Environnement de la démonstration

WordPress 6.x, PHP 8.3. La base de la démonstration locale est SQLite ; le code est
agnostique et fonctionne sur MySQL/MariaDB, qui reste la cible en production.

---

Anirudha Talmale — août 2026
