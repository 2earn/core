import json
import re
from collections import OrderedDict
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
TRANSLATION_FILE = ROOT / "resources" / "lang" / "es.json"
EN_FILE = ROOT / "resources" / "lang" / "en.json"
SUFFIX = " ES"
PLACEHOLDER_RE = re.compile(r"^[A-Za-z0-9._-]+(?:\s+ES)?$")
STOP_WORDS = {"notifications", "notification", "settings", "setting"}

PLACEHOLDER_ES_MAP = {
    "notifications.settings.order_completed": "Configuración de notificaciones de pedidos completados",
    "notifications.order_completed.action": "Ver pedido completado",
    "notifications.settings.share_purchase": "Configuración de notificaciones de compra de acciones",
    "notifications.share_purchase.action": "Ver detalles de compra de acciones",
    "notifications.settings.survey_participation": "Configuración de notificaciones de participación en encuestas",
    "notifications.survey_participation.action": "Ver encuesta",
    "notifications.settings.cash_to_bfs": "Configuración de notificaciones de transferencias de efectivo a BFS",
    "notifications.cash_to_bfs.action": "Ver detalles de la transferencia de efectivo a BFS",
    "notifications.settings.delivery_sms": "Configuración de notificaciones SMS de entrega",
    "notifications.delivery_sms.action": "Ver detalles de la entrega",
    "notifications.settings.financial_request_sent": "Configuración de notificaciones de solicitudes financieras enviadas",
    "notifications.financial_request_sent.action": "Ver solicitud financiera",
}

SEGMENT_ES_MAP = {
    "notifications": "Notificaciones",
    "notification": "Notificación",
    "settings": "Configuración",
    "setting": "Configuración",
    "order": "Pedido",
    "completed": "completado",
    "share": "Acción",
    "purchase": "Compra",
    "action": "Acción",
    "survey": "Encuesta",
    "participation": "Participación",
    "cash": "Efectivo",
    "to": "a",
    "bfs": "BFS",
    "delivery": "Entrega",
    "sms": "SMS",
    "financial": "Financiera",
    "request": "Solicitud",
    "sent": "enviada",
}

EN_TRANSLATIONS = {}
if EN_FILE.exists():
    with EN_FILE.open("r", encoding="utf-8") as fh:
        EN_TRANSLATIONS = json.load(fh)


def humanize_segment(segment: str) -> str:
    lowered = segment.lower()
    if lowered in SEGMENT_ES_MAP:
        return SEGMENT_ES_MAP[lowered]
    words = re.split(r"[_-]+", segment)
    return " ".join(word.capitalize() for word in words if word)


def normalize_placeholder(value: str) -> str:
    return value.replace(SUFFIX, "").strip()


def placeholder_to_phrase(key: str, value: str) -> str:
    if key in PLACEHOLDER_ES_MAP:
        return PLACEHOLDER_ES_MAP[key]

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

