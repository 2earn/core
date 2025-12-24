import json
import re
from collections import OrderedDict
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
TRANSLATION_FILE = ROOT / "resources" / "lang" / "de.json"
EN_FILE = ROOT / "resources" / "lang" / "en.json"
SUFFIX = " DE"
PLACEHOLDER_RE = re.compile(r"^[A-Za-z0-9._-]+(?:\s+DE)?$")
STOP_WORDS = {"notifications", "notification", "settings", "setting"}

PLACEHOLDER_DE_MAP = {
    "notifications.settings.order_completed": "Benachrichtigungseinstellungen für abgeschlossene Bestellungen",
    "notifications.order_completed.action": "Abgeschlossene Bestellung anzeigen",
    "notifications.settings.share_purchase": "Benachrichtigungseinstellungen für Aktienkäufe",
    "notifications.share_purchase.action": "Details zum Aktienkauf anzeigen",
    "notifications.settings.survey_participation": "Benachrichtigungseinstellungen für Umfrageteilnahme",
    "notifications.survey_participation.action": "Umfrage anzeigen",
    "notifications.settings.cash_to_bfs": "Benachrichtigungseinstellungen für Cash-zu-BFS-Übertragungen",
    "notifications.cash_to_bfs.action": "Details zur Cash-zu-BFS-Übertragung anzeigen",
    "notifications.settings.delivery_sms": "Benachrichtigungseinstellungen für Liefer-SMS",
    "notifications.delivery_sms.action": "Lieferdetails anzeigen",
    "notifications.settings.financial_request_sent": "Benachrichtigungseinstellungen für versandte Finanzierungsanfragen",
    "notifications.financial_request_sent.action": "Finanzierungsanfrage anzeigen",
}

SEGMENT_DE_MAP = {
    "notifications": "Benachrichtigungen",
    "notification": "Benachrichtigung",
    "settings": "Einstellungen",
    "setting": "Einstellung",
    "order": "Bestellung",
    "completed": "abgeschlossen",
    "share": "Aktie",
    "purchase": "Kauf",
    "action": "Aktion",
    "survey": "Umfrage",
    "participation": "Teilnahme",
    "cash": "Cash",
    "to": "zu",
    "bfs": "BFS",
    "delivery": "Lieferung",
    "sms": "SMS",
    "financial": "Finanz",
    "request": "Anfrage",
    "sent": "gesendet",
}

EN_TRANSLATIONS = {}
if EN_FILE.exists():
    with EN_FILE.open("r", encoding="utf-8") as fh:
        EN_TRANSLATIONS = json.load(fh)


def humanize_segment(segment: str) -> str:
    lowered = segment.lower()
    if lowered in SEGMENT_DE_MAP:
        return SEGMENT_DE_MAP[lowered]
    words = re.split(r"[_-]+", segment)
    return " ".join(word.capitalize() for word in words if word)


def normalize_placeholder(value: str) -> str:
    return value.replace(SUFFIX, "").strip()


def placeholder_to_phrase(key: str, value: str) -> str:
    if key in PLACEHOLDER_DE_MAP:
        return PLACEHOLDER_DE_MAP[key]

    base = normalize_placeholder(value)
    segments = [seg for seg in base.split(".") if seg]
    filtered = [seg for seg in segments if seg not in STOP_WORDS] or segments
    phrase = " ".join(humanize_segment(seg) for seg in filtered).strip()

    if not phrase and key in EN_TRANSLATIONS:
        return EN_TRANSLATIONS[key]

    return phrase or base


def clean_value(key: str, raw: str) -> str:
    value = raw.rstrip()

    if value.endswith(SUFFIX):
        value = value[: -len(SUFFIX)].rstrip()

    if PLACEHOLDER_RE.fullmatch(value) and "." in value:
        value = placeholder_to_phrase(key, value)

    return value


def main() -> None:
    with TRANSLATION_FILE.open("r", encoding="utf-8") as fh:
        translations = json.load(fh, object_pairs_hook=OrderedDict)

    changes = 0
    for key, value in translations.items():
        if isinstance(value, str):
            cleaned = clean_value(key, value)
            if cleaned != value:
                translations[key] = cleaned
                changes += 1

    if changes:
        with TRANSLATION_FILE.open("w", encoding="utf-8") as fh:
            json.dump(translations, fh, ensure_ascii=False, separators=(",", ":"))

    print(f"Updated {changes} entries.")


if __name__ == "__main__":
    main()

