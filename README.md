# airplanes-web

Jednoduchá webová prezentácia o vybraných lietadlách.

Autor: Sebastian Štutika

Stručný popis

Tento projekt je statická a mierne dynamická webová stránka (HTML, CSS, JavaScript) s jednoduchým PHP backendom pre spracovanie formulára. Obsahuje domovskú stránku s bannerom, galériu obrázkov, stránku s úlohami a kontaktný formulár, ktorého dáta sa ukladajú do CSV súboru.

Rýchly štart (lokálne)

1. Uistite sa, že máte nainštalované PHP 7.4+.
2. Spustite vstavaný PHP server z koreňa projektu:

```bash
php -S 127.0.0.1:8000 -t /Users/peterszeles/WebstormProjects/airplanes-web
```

3. Otvorte v prehliadači: http://127.0.0.1:8000/index.html

Dôležité priečinky a súbory

- `index.html` — domovská stránka s bannerom
- `gallery.html` — galéria obrázkov
- `contact.html` — kontaktný formulár (odosiela do `php/submit.php`)
- `ulohy.html` — zoznam úloh
- `css/styles.css` — hlavné štýly
- `js/main.js`, `js/validate.js` — klientské skripty
- `php/submit.php` — serverové spracovanie formulára
- `php/get_csrf.php` — poskytuje CSRF token pre formulár
- `php/result.php` — zobrazenie výsledku uloženého záznamu
- `data/submissions.csv` — uložené záznamy z formulára (csv)
- `uploads/` — miesto pre nahrané súbory (zakázané indexovanie)
- `images/` — vložené obrázky (vrátane banneru)

Licence

Projekt obsahuje licenciu v `LICENSE`. Ak použijete ďalší obsah alebo šablóny z internetu, uveďte ich autora v dokumentácii.

Viac informácií a kompletný popis krokov, vrátane vysvetlenia pre začiatočníkov, nájdete v `docs/documentation.md`.
