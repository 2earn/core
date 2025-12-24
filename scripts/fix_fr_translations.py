import json
import re
from collections import OrderedDict
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
FR_FILE = ROOT / "resources" / "lang" / "fr.json"
EN_FILE = ROOT / "resources" / "lang" / "en.json"
SUFFIX = " FR"
PLACEHOLDER_RE = re.compile(r"^[A-Za-z0-9._-]+(?:\s+FR)?$")
STOP_WORDS = {"notifications", "notification", "settings", "setting"}

PLACEHOLDER_FR_MAP = {
    "notifications.settings.order_completed": "Paramètres des notifications de commande terminée",
    "notifications.order_completed.action": "Afficher la commande terminée",
    "notifications.settings.share_purchase": "Paramètres des notifications d'achat d'actions",
    "notifications.share_purchase.action": "Afficher les détails d'achat d'actions",
    "notifications.settings.survey_participation": "Paramètres des notifications de participation au sondage",
    "notifications.survey_participation.action": "Afficher le sondage",
}

SEGMENT_FR_MAP = {
    "notifications": "Notifications",
    "notification": "Notification",
    "settings": "Paramètres",
    "setting": "Paramètres",
    "order": "Commande",
    "completed": "Terminée",
    "share": "Action",
    "purchase": "Achat",
    "action": "Action",
    "survey": "Sondage",
    "participation": "Participation",
    "cash": "Cash",
    "to": "vers",
    "bfs": "BFS",
    "delivery": "Livraison",
    "sms": "SMS",
    "financial": "Financier",
    "request": "Demande",
    "sent": "Envoyée",
}


def humanize_segment(segment: str) -> str:
    lowered = segment.lower()
    if lowered in SEGMENT_FR_MAP:
        return SEGMENT_FR_MAP[lowered]
    words = re.split(r"[_-]+", segment)
    return " ".join(word.capitalize() for word in words if word)


def normalize_placeholder(value: str) -> str:
    return value.replace(SUFFIX, "").strip()


def placeholder_to_phrase(key: str, value: str) -> str:
    if key in PLACEHOLDER_FR_MAP:
        return PLACEHOLDER_FR_MAP[key]

    base = normalize_placeholder(value)
    segments = [seg for seg in base.split(".") if seg]
    filtered = [seg for seg in segments if seg not in STOP_WORDS] or segments
    return " ".join(humanize_segment(seg) for seg in filtered)


def clean_value(key: str, raw: str) -> str:
    value = raw.rstrip()

    if value.endswith(SUFFIX):
        value = value[: -len(SUFFIX)].rstrip()

    if PLACEHOLDER_RE.fullmatch(value) and "." in value:
        value = placeholder_to_phrase(key, value)

    return value


def main() -> None:
    with FR_FILE.open("r", encoding="utf-8") as fh:
        translations = json.load(fh, object_pairs_hook=OrderedDict)

    en_translations = {}
    if EN_FILE.exists():
        with EN_FILE.open("r", encoding="utf-8") as fh:
            en_translations = json.load(fh)

    changes = 0
    for key, value in translations.items():
        if isinstance(value, str):
            cleaned = clean_value(key, value)
            if cleaned == value and key not in PLACEHOLDER_FR_MAP and key in en_translations:
                # Use English fallback only if the original looked like a placeholder
                if PLACEHOLDER_RE.fullmatch(value):
                    cleaned = en_translations[key]
            if cleaned != value:
                translations[key] = cleaned
                changes += 1

    if changes:
        with FR_FILE.open("w", encoding="utf-8") as fh:
            json.dump(translations, fh, ensure_ascii=False, separators=(",", ":"))

    print(f"Updated {changes} entries.")


if __name__ == "__main__":
    main()

