import json
import re
from collections import OrderedDict
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
TRANSLATION_FILE = ROOT / "resources" / "lang" / "tr.json"
EN_FILE = ROOT / "resources" / "lang" / "en.json"
SUFFIXES = (" TU", " TR")
PLACEHOLDER_RE = re.compile(r"^[A-Za-z0-9._-]+(?:\s+TU)?$")
STOP_WORDS = {"notifications", "notification", "settings", "setting"}

PLACEHOLDER_TR_MAP = {
    "notifications.settings.order_completed": "Tamamlanan sipariş bildirim ayarları",
    "notifications.order_completed.action": "Tamamlanan siparişi görüntüle",
    "notifications.settings.share_purchase": "Hisse alımı bildirim ayarları",
    "notifications.share_purchase.action": "Hisse alımı detaylarını görüntüle",
    "notifications.settings.survey_participation": "Anket katılımı bildirim ayarları",
    "notifications.survey_participation.action": "Anketi görüntüle",
    "notifications.settings.cash_to_bfs": "Nakit'ten BFS'ye transfer bildirim ayarları",
    "notifications.cash_to_bfs.action": "Nakit'ten BFS'ye transfer detaylarını görüntüle",
    "notifications.settings.delivery_sms": "Teslimat SMS bildirim ayarları",
    "notifications.delivery_sms.action": "Teslimat detaylarını görüntüle",
    "notifications.settings.financial_request_sent": "Gönderilen finansal talepler için bildirim ayarları",
    "notifications.financial_request_sent.action": "Finansal talebi görüntüle",
}

SEGMENT_TR_MAP = {
    "notifications": "Bildirimler",
    "notification": "Bildirim",
    "settings": "Ayarlar",
    "setting": "Ayar",
    "order": "Sipariş",
    "completed": "tamamlandı",
    "share": "Hisse",
    "purchase": "Alım",
    "action": "İşlem",
    "survey": "Anket",
    "participation": "Katılım",
    "cash": "Nakit",
    "to": "to",
    "bfs": "BFS",
    "delivery": "Teslimat",
    "sms": "SMS",
    "financial": "Finansal",
    "request": "Talep",
    "sent": "gönderildi",
}

EN_TRANSLATIONS = {}
if EN_FILE.exists():
    with EN_FILE.open("r", encoding="utf-8") as fh:
        EN_TRANSLATIONS = json.load(fh)


def humanize_segment(segment: str) -> str:
    lowered = segment.lower()
    if lowered in SEGMENT_TR_MAP:
        return SEGMENT_TR_MAP[lowered]
    words = re.split(r"[_-]+", segment)
    return " ".join(word.capitalize() for word in words if word)


def normalize_placeholder(value: str) -> str:
    cleaned = value
    for suffix in SUFFIXES:
        if cleaned.endswith(suffix):
            cleaned = cleaned[: -len(suffix)]
    return cleaned.strip()


def placeholder_to_phrase(key: str, value: str) -> str:
    if key in PLACEHOLDER_TR_MAP:
        return PLACEHOLDER_TR_MAP[key]

    base = normalize_placeholder(value)
    segments = [seg for seg in base.split(".") if seg]
    filtered = [seg for seg in segments if seg not in STOP_WORDS] or segments
    phrase = " ".join(humanize_segment(seg) for seg in filtered).strip()

    if not phrase and key in EN_TRANSLATIONS:
        return EN_TRANSLATIONS[key]

    return phrase or base


def clean_value(key: str, raw: str) -> str:
    value = raw.rstrip()
    for suffix in SUFFIXES:
        if value.endswith(suffix):
            value = value[: -len(suffix)].rstrip()

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
