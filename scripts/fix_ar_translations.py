import json
import re
from collections import OrderedDict
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
TRANSLATION_FILE = ROOT / "resources" / "lang" / "ar.json"
EN_FILE = ROOT / "resources" / "lang" / "en.json"
SUFFIX = " AR"
PLACEHOLDER_RE = re.compile(r"^[A-Za-z0-9._-]+(?:\s+AR)?$")

# Static map for known placeholders
PLACEHOLDER_AR_MAP = {
    "notifications.settings.cash_to_bfs": "إعدادات الإشعارات لتمويل BFS",
    "notifications.cash_to_bfs.action": "عرض تفاصيل تحويل BFS",
    "notifications.settings.delivery_sms": "إعدادات إشعارات تسليم الطلبات",
    "notifications.delivery_sms.action": "عرض تفاصيل التسليم",
    "notifications.settings.financial_request_sent": "إعدادات إشعارات طلبات التمويل",
    "notifications.financial_request_sent.action": "متابعة طلب التمويل",
    "notifications.settings.order_completed": "إعدادات إشعارات الطلب المكتمل",
    "notifications.order_completed.action": "عرض الطلب المكتمل",
    "notifications.settings.share_purchase": "إعدادات إشعارات شراء الأسهم",
    "notifications.share_purchase.action": "عرض تفاصيل شراء الأسهم",
    "notifications.settings.survey_participation": "إعدادات إشعارات المشاركة في الاستطلاع",
    "notifications.survey_participation.action": "عرض الاستطلاع",
}

# Transliteration map for general segments
SEGMENT_AR_MAP = {
    "notifications": "الإشعارات",
    "notification": "الإشعار",
    "settings": "الإعدادات",
    "order": "الطلب",
    "completed": "المكتمل",
    "share": "السهم",
    "purchase": "الشراء",
    "action": "الإجراء",
    "delivery": "التسليم",
    "sms": "رسالة نصية",
    "financial": "المالي",
    "request": "الطلب",
    "sent": "المُرسَل",
    "survey": "الاستطلاع",
    "participation": "المشاركة",
    "cash": "نقدي",
    "bfs": "BFS",
    "to": "إلى",
}


def clean_value(key: str, value: str) -> str:
    cleaned = value.rstrip()
    if cleaned.endswith(SUFFIX):
        cleaned = cleaned[: -len(SUFFIX)].rstrip()

    if key in PLACEHOLDER_AR_MAP:
        return PLACEHOLDER_AR_MAP[key]

    if PLACEHOLDER_RE.fullmatch(cleaned) and "." in cleaned:
        segments = [seg for seg in cleaned.replace(" AR", "").split(".") if seg]
        arabic_segments = [SEGMENT_AR_MAP.get(seg.lower(), seg) for seg in segments]
        return " ".join(arabic_segments)

    return cleaned


def main() -> None:
    with TRANSLATION_FILE.open("r", encoding="utf-8") as fh:
        translations = json.load(fh, object_pairs_hook=OrderedDict)

    changes = 0
    for k, v in translations.items():
        if isinstance(v, str):
            new_v = clean_value(k, v)
            if new_v != v:
                translations[k] = new_v
                changes += 1

    if changes:
        with TRANSLATION_FILE.open("w", encoding="utf-8") as fh:
            json.dump(translations, fh, ensure_ascii=False, separators=(",", ":"))

    print(f"Updated {changes} entries.")


if __name__ == "__main__":
    main()

