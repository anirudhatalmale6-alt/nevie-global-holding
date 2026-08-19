/**
 * NEVIE-GLOBAL — interactions du thème.
 * Sans dépendance, chargé en différé. Rien ici ne dépose de cookie ni n'appelle
 * un service tiers : le consentement est mémorisé dans le stockage local.
 */
(function () {
	'use strict';

	/* --- Menu mobile ------------------------------------------------------ */
	var bascule = document.querySelector('.bascule');
	var nav = document.getElementById('nav-principale');

	if (bascule && nav) {
		bascule.addEventListener('click', function () {
			var ouvert = nav.getAttribute('data-ouvert') === 'true';
			nav.setAttribute('data-ouvert', ouvert ? 'false' : 'true');
			bascule.setAttribute('aria-expanded', ouvert ? 'false' : 'true');
		});

		/* Échap referme le menu et rend le focus au bouton (WCAG 2.1.2). */
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && nav.getAttribute('data-ouvert') === 'true') {
				nav.setAttribute('data-ouvert', 'false');
				bascule.setAttribute('aria-expanded', 'false');
				bascule.focus();
			}
		});
	}

	/* --- Bannière cookies -------------------------------------------------- */
	var banniere = document.getElementById('banniere-cookies');
	if (!banniere) {
		return;
	}

	var CLE = 'ng_consentement';
	var choix = null;

	try {
		choix = window.localStorage.getItem(CLE);
	} catch (e) {
		choix = null; // navigation privée : on redemandera, on ne casse rien.
	}

	if (!choix) {
		banniere.hidden = false;
	}

	banniere.addEventListener('click', function (e) {
		var bouton = e.target.closest('[data-cookies]');
		if (!bouton) {
			return;
		}

		var action = bouton.getAttribute('data-cookies');

		if (action === 'personnaliser') {
			window.location.href = banniere.querySelector('a').getAttribute('href') + '#cookies';
			return;
		}

		try {
			window.localStorage.setItem(CLE, action);
			window.localStorage.setItem(CLE + '_date', new Date().toISOString());
		} catch (err) {
			/* Le refus de stockage n'empêche pas la fermeture. */
		}

		banniere.hidden = true;

		/*
		 * Point d'accroche pour un éventuel outil de mesure d'audience :
		 * il ne sera chargé que si l'événement porte « accepter ».
		 * Tant qu'aucun outil n'est retenu par NEVIE-GLOBAL SAS, rien ne se produit.
		 */
		document.dispatchEvent(new CustomEvent('ng:consentement', { detail: { choix: action } }));
	});
})();
