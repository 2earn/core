import json
import re
from collections import OrderedDict
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
TRANSLATION_FILE = ROOT / "resources" / "lang" / "ru.json"
EN_FILE = ROOT / "resources" / "lang" / "en.json"
SUFFIX = " RU"
PLACEHOLDER_RE = re.compile(r"^[A-Za-z0-9._-]+(?:\s+RU)?$")
STOP_WORDS = {"notifications", "notification", "settings", "setting"}

PLACEHOLDER_RU_MAP = {
    "notifications.settings.order_completed": "Настройки уведомлений о завершённых заказах",
    "notifications.order_completed.action": "Просмотреть завершённый заказ",
    "notifications.settings.share_purchase": "Настройки уведомлений о покупке акций",
    "notifications.share_purchase.action": "Просмотреть детали покупки акций",
    "notifications.settings.survey_participation": "Настройки уведомлений об участии в опросах",
    "notifications.survey_participation.action": "Просмотреть опрос",
    "notifications.settings.cash_to_bfs": "Настройки уведомлений о переводах из кэша в BFS",
    "notifications.cash_to_bfs.action": "Просмотреть детали перевода из кэша в BFS",
    "notifications.settings.delivery_sms": "Настройки SMS-уведомлений о доставке",
    "notifications.delivery_sms.action": "Просмотреть детали доставки",
    "notifications.settings.financial_request_sent": "Настройки уведомлений об отправленных финансовых запросах",
    "notifications.financial_request_sent.action": "Просмотреть финансовый запрос",
}

SEGMENT_RU_MAP = {
    "notifications": "Уведомления",
    "notification": "Уведомление",
    "settings": "Настройки",
    "setting": "Настройка",
    "order": "Заказ",
    "completed": "завершён",
    "share": "Акция",
    "purchase": "Покупка",
    "action": "Действие",
    "survey": "Опрос",
    "participation": "Участие",
    "cash": "Кэш",
    "to": "в",
    "bfs": "BFS",
    "delivery": "Доставка",
    "sms": "SMS",
    "financial": "Финансовый",
    "request": "Запрос",
    "sent": "отправлен",
}

EN_TRANSLATIONS = {}
if EN_FILE.exists():
    with EN_FILE.open("r", encoding="utf-8") as fh:
        EN_TRANSLATIONS = json.load(fh)


def humanize_segment(segment: str) -> str:
    lowered = segment.lower()
    if lowered in SEGMENT_RU_MAP:
        return SEGMENT_RU_MAP[lowered]
    words = re.split(r"[_-]+", segment)
    return " ".join(word.capitalize() for word in words if word)


def normalize_placeholder(value: str) -> str:
    return value.replace(SUFFIX, "").strip()


def placeholder_to_phrase(key: str, value: str) -> str:
    if key in PLACEHOLDER_RU_MAP:
        return PLACEHOLDER_RU_MAP[key]

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

