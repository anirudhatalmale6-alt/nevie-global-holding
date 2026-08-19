"""Recette front : consentement RGPD, page Mentions légales, navigation mobile."""
from playwright.sync_api import sync_playwright

PORT = open("/var/lib/freelancer/projects/40657028/build/port.txt").read().strip()
BASE = f"http://localhost:{PORT}"
res = []


def verdict(libelle, ok, detail=""):
    res.append(ok)
    print(f"{'PASS' if ok else 'ECHEC'}  {libelle}" + (f"  ({detail})" if detail else ""))


with sync_playwright() as p:
    b = p.chromium.launch()
    ctx = b.new_context(viewport={"width": 1280, "height": 800})
    pg = ctx.new_page()

    # --- Consentement ------------------------------------------------------
    pg.goto(BASE + "/", wait_until="networkidle")
    verdict("La bannière cookies s'affiche à la première visite",
            pg.locator("#banniere-cookies").is_visible())

    # Aucun script tiers avant choix : on liste les requêtes sortantes
    externes = []
    pg2 = ctx.new_page()
    pg2.on("request", lambda r: externes.append(r.url) if f":{PORT}" not in r.url and not r.url.startswith("data:") else None)
    pg2.goto(BASE + "/", wait_until="networkidle")
    pg2.wait_for_timeout(800)
    verdict("Aucune requête vers un domaine externe avant consentement",
            len(externes) == 0, f"{len(externes)} requête(s)")
    pg2.close()

    pg.click('[data-cookies="refuser"]')
    pg.wait_for_timeout(300)
    verdict("« Refuser » referme la bannière", pg.locator("#banniere-cookies").is_hidden())

    pg.reload(wait_until="networkidle")
    pg.wait_for_timeout(400)
    verdict("Le choix est mémorisé après rechargement", pg.locator("#banniere-cookies").is_hidden())

    pg.goto(BASE + "/politique-de-confidentialite/", wait_until="networkidle")
    pg.click("#rouvrir-cookies")
    pg.wait_for_timeout(300)
    verdict("« Modifier mon choix » rouvre la bannière", pg.locator("#banniere-cookies").is_visible())

    # --- Mentions légales : rien d'incomplet côté public -------------------
    pub = ctx.new_page()
    pub.goto(BASE + "/mentions-legales/", wait_until="networkidle")
    corps = pub.inner_text("body")
    verdict("Mentions légales — aucune mention « à compléter » publiée",
            "à compléter" not in corps.lower())
    verdict("Mentions légales — aucune ligne SIRET vide publiée", "SIRET" not in corps)
    verdict("Mentions légales — aucune ligne Hébergeur vide publiée", "Hébergeur" not in corps)
    verdict("Mentions légales — l'avertissement de pré-production reste invisible au public",
            "pré-production" not in corps.lower())
    pub.close()

    # --- Formulaires non connectés ----------------------------------------
    f = ctx.new_page()
    f.goto(BASE + "/ceder-son-entreprise/", wait_until="networkidle")
    verdict("Formulaire de cession — envoi désactivé tant que n8n n'est pas branché",
            f.locator('form button[type="submit"]').is_disabled())
    verdict("Formulaire de cession — les 13 champs de la Partie 6 sont présents",
            f.locator("form input, form select, form textarea").count() >= 15)
    f.close()

    # --- Navigation mobile -------------------------------------------------
    m = b.new_page(viewport={"width": 390, "height": 780})
    m.goto(BASE + "/", wait_until="networkidle")
    verdict("Mobile — le menu est replié au chargement",
            not m.locator("#nav-principale").is_visible())
    m.click(".bascule")
    m.wait_for_timeout(300)
    verdict("Mobile — le bouton Menu déplie la navigation",
            m.locator("#nav-principale").is_visible())
    m.keyboard.press("Escape")
    m.wait_for_timeout(300)
    verdict("Mobile — Échap referme la navigation",
            not m.locator("#nav-principale").is_visible())
    m.close()

    b.close()

print(f"\n{sum(res)}/{len(res)} tests réussis")
raise SystemExit(0 if all(res) else 1)
