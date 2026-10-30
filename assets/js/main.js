/* Euroccat — interactions du thème (sans dépendance). */
(function () {
	'use strict';

	var doc = document.documentElement;
	var body = document.body;
	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* ---------- En-tête : état au défilement + masquage en descente ---------- */
	var header = document.getElementById('site-header');
	var progress = document.querySelector('.progress span');
	var lastY = window.scrollY;
	var ticking = false;

	function onScroll() {
		var y = window.scrollY;
		if (header) {
			header.classList.toggle('is-scrolled', y > 30);
			var hide = y > 400 && y > lastY && !body.classList.contains('menu-open');
			header.classList.toggle('is-hidden', hide);
		}
		if (progress) {
			var max = doc.scrollHeight - window.innerHeight;
			progress.style.setProperty('--p', max > 0 ? Math.min(1, y / max) : 0);
		}
		lastY = y;
		ticking = false;
	}
	window.addEventListener('scroll', function () {
		if (!ticking) {
			window.requestAnimationFrame(onScroll);
			ticking = true;
		}
	}, { passive: true });
	onScroll();

	/* ---------- Menu mobile ---------- */
	var toggle = document.querySelector('[data-menu-toggle]');
	var nav = document.getElementById('main-nav');
	function closeMenu() {
		body.classList.remove('menu-open');
		if (toggle) { toggle.setAttribute('aria-expanded', 'false'); }
	}
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var open = body.classList.toggle('menu-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		nav.addEventListener('click', function (e) {
			var link = e.target.closest('a');
			if (!link) { return; }
			var li = link.parentElement;
			var isMobile = window.matchMedia('(max-width: 1080px)').matches;
			var hasSub = li && li.classList.contains('menu-item-has-children');
			// Sur mobile ou tactile : premier appui ouvre le sous-menu.
			if (hasSub && (isMobile || matchMedia('(hover: none)').matches) && !li.classList.contains('is-open')) {
				e.preventDefault();
				nav.querySelectorAll('.is-open').forEach(function (el) { if (el !== li) { el.classList.remove('is-open'); } });
				li.classList.add('is-open');
				return;
			}
			closeMenu();
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') { closeMenu(); }
		});
	}

	/* ---------- Mode sombre ---------- */
	var themeBtn = document.querySelector('[data-theme-toggle]');
	if (themeBtn) {
		themeBtn.addEventListener('click', function () {
			var current = doc.getAttribute('data-theme') || 'light';
			var next = current === 'dark' ? 'light' : 'dark';
			doc.setAttribute('data-theme', next);
			try { localStorage.setItem('euroccat-theme', next); } catch (err) { /* stockage indisponible */ }
		});
	}

	/* ---------- Recherche plein écran ---------- */
	var overlay = document.getElementById('search-overlay');
	var openSearch = document.querySelector('[data-search-open]');
	if (overlay && openSearch) {
		var closeSearch = function () { overlay.hidden = true; body.style.overflow = ''; openSearch.focus(); };
		openSearch.addEventListener('click', function () {
			overlay.hidden = false;
			body.style.overflow = 'hidden';
			var input = overlay.querySelector('input[type="search"]');
			if (input) { setTimeout(function () { input.focus(); }, 50); }
		});
		overlay.querySelector('[data-search-close]').addEventListener('click', closeSearch);
		overlay.addEventListener('click', function (e) { if (e.target === overlay) { closeSearch(); } });
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !overlay.hidden) { closeSearch(); } });
	}

	/* ---------- Découpage des titres en mots pour l'animation ---------- */
	document.querySelectorAll('.split-words').forEach(function (el) {
		if (el.dataset.split) { return; }
		el.dataset.split = '1';
		var i = 0;
		var walk = function (node) {
			Array.prototype.slice.call(node.childNodes).forEach(function (child) {
				if (child.nodeType === 3) {
					var frag = document.createDocumentFragment();
					child.textContent.split(/(\s+)/).forEach(function (part) {
						if (!part) { return; }
						if (/^\s+$/.test(part)) { frag.appendChild(document.createTextNode(part)); return; }
						var w = document.createElement('span');
						w.className = 'w';
						var inner = document.createElement('span');
						inner.style.setProperty('--wi', i++);
						inner.textContent = part;
						w.appendChild(inner);
						frag.appendChild(w);
					});
					child.parentNode.replaceChild(frag, child);
				} else if (child.nodeType === 1) {
					walk(child);
				}
			});
		};
		walk(el);
	});

	/* ---------- Apparition au défilement ---------- */
	var revealEls = document.querySelectorAll('.reveal, .split-words');
	if ('IntersectionObserver' in window && !reduce) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					io.unobserve(entry.target);
				}
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
		revealEls.forEach(function (el) { io.observe(el); });
	} else {
		revealEls.forEach(function (el) { el.classList.add('is-visible'); });
	}

	/* ---------- Compteurs animés ---------- */
	var counters = document.querySelectorAll('[data-count]');
	if ('IntersectionObserver' in window && !reduce) {
		var co = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) { return; }
				var el = entry.target;
				var target = parseInt(el.getAttribute('data-count'), 10);
				var original = el.textContent;
				if (isNaN(target)) { return; }
				var start = target > 1000 ? target - 120 : 0;
				var t0 = null;
				var dur = 1800;
				var step = function (ts) {
					if (!t0) { t0 = ts; }
					var p = Math.min(1, (ts - t0) / dur);
					var eased = 1 - Math.pow(1 - p, 4);
					el.textContent = Math.round(start + (target - start) * eased);
					if (p < 1) { requestAnimationFrame(step); } else { el.textContent = original; }
				};
				requestAnimationFrame(step);
				co.unobserve(el);
			});
		}, { threshold: 0.6 });
		counters.forEach(function (el) { co.observe(el); });
	}

	/* ---------- Emblème 3D qui suit la souris ---------- */
	var tilt = document.querySelector('[data-tilt]');
	if (tilt && !reduce && matchMedia('(hover: hover)').matches) {
		var emblem = tilt.querySelector('.emblem');
		var hero = document.querySelector('[data-hero]');
		hero.addEventListener('mousemove', function (e) {
			var r = hero.getBoundingClientRect();
			var x = (e.clientX - r.left) / r.width - 0.5;
			var y = (e.clientY - r.top) / r.height - 0.5;
			emblem.style.setProperty('--ry', (x * 18).toFixed(2) + 'deg');
			emblem.style.setProperty('--rx', (-y * 18).toFixed(2) + 'deg');
		});
		hero.addEventListener('mouseleave', function () {
			emblem.style.setProperty('--ry', '0deg');
			emblem.style.setProperty('--rx', '0deg');
		});
	}


	/* ---------- Frise : flèches + glisser à la souris ---------- */
	var tl = document.querySelector('[data-timeline] .timeline__list');
	if (tl) {
		var stepSize = function () {
			var item = tl.querySelector('.tl-item');
			return item ? item.getBoundingClientRect().width + 22 : 400;
		};
		var prev = document.querySelector('[data-tl-prev]');
		var next = document.querySelector('[data-tl-next]');
		if (prev) { prev.addEventListener('click', function () { tl.scrollBy({ left: -stepSize(), behavior: 'smooth' }); }); }
		if (next) { next.addEventListener('click', function () { tl.scrollBy({ left: stepSize(), behavior: 'smooth' }); }); }

		var down = false, startX = 0, startScroll = 0, moved = false;
		tl.addEventListener('pointerdown', function (e) {
			if (e.pointerType !== 'mouse') { return; }
			down = true; moved = false; startX = e.clientX; startScroll = tl.scrollLeft;
		});
		window.addEventListener('pointermove', function (e) {
			if (!down) { return; }
			var dx = e.clientX - startX;
			if (Math.abs(dx) > 5) { moved = true; tl.classList.add('is-dragging'); }
			tl.scrollLeft = startScroll - dx;
		});
		window.addEventListener('pointerup', function () {
			down = false;
			tl.classList.remove('is-dragging');
		});
		tl.addEventListener('click', function (e) {
			if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; }
		}, true);
	}

	/* ---------- Bibliothèque : recherche + filtres ---------- */
	var libList = document.querySelector('[data-lib-list]');
	if (libList) {
		var input = document.querySelector('[data-lib-search]');
		var filters = document.querySelectorAll('[data-lib-filters] .filter');
		var empty = document.querySelector('[data-lib-empty]');
		var year = '';
		var normalize = function (s) { return s.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, ''); };
		var apply = function () {
			var q = normalize(input ? input.value.trim() : '');
			var shown = 0;
			libList.querySelectorAll('.doc').forEach(function (d) {
				var ok = (!year || d.dataset.year === year) && (!q || d.dataset.title.indexOf(q) !== -1);
				d.hidden = !ok;
				if (ok) { shown++; d.classList.add('is-visible'); }
			});
			if (empty) { empty.hidden = shown > 0; }
		};
		if (input) { input.addEventListener('input', apply); }
		filters.forEach(function (f) {
			f.addEventListener('click', function () {
				filters.forEach(function (o) { o.classList.remove('is-active'); });
				f.classList.add('is-active');
				year = f.dataset.year;
				apply();
			});
		});
	}

	/* ---------- Copier le lien d'un article ---------- */
	var copyBtn = document.querySelector('[data-copy-link]');
	if (copyBtn && navigator.clipboard) {
		copyBtn.addEventListener('click', function () {
			navigator.clipboard.writeText(window.location.href).then(function () {
				copyBtn.classList.add('is-copied');
				setTimeout(function () { copyBtn.classList.remove('is-copied'); }, 1600);
			});
		});
	}

	/* ---------- Visionneuse pour les affiches ---------- */
	document.querySelectorAll('.poster-gallery a, .entry-content a[href$=".jpg"], .entry-content a[href$=".png"]').forEach(function (a) {
		a.addEventListener('click', function (e) {
			e.preventDefault();
			var box = document.createElement('div');
			box.className = 'lightbox';
			box.innerHTML = '<img alt="" src="' + a.getAttribute('href') + '"><button type="button" aria-label="Fermer">×</button>';
			body.appendChild(box);
			body.style.overflow = 'hidden';
			var close = function () { box.remove(); body.style.overflow = ''; document.removeEventListener('keydown', esc); };
			var esc = function (ev) { if (ev.key === 'Escape') { close(); } };
			box.addEventListener('click', close);
			document.addEventListener('keydown', esc);
		});
	});

	/* ---------- Retour en haut ---------- */
	var top = document.querySelector('[data-back-to-top]');
	if (top) {
		top.addEventListener('click', function (e) {
			e.preventDefault();
			window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
		});
	}
})();
