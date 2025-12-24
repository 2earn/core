import json
import re
from collections import OrderedDict
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
TRANSLATION_FILE = ROOT / "resources" / "lang" / "en.json"
SUFFIX = " EN"
PLACEHOLDER_RE = re.compile(r"^[A-Za-z0-9._-]+$")
STOP_WORDS = {"notifications", "notification", "settings", "setting"}
GENERIC_SUFFIXES = {
    "action",
    "title",
    "subtitle",
    "description",
    "body",
    "message",
    "label",
    "button",
    "cta",
    "text",
    "details",
    "note",
}


def humanize_segment(segment: str) -> str:
    parts = re.split(r"[_-]+", segment)
    return " ".join(word.capitalize() for word in parts if word)


def placeholder_to_phrase(value: str) -> str:
    segments = [seg for seg in value.split(".") if seg]
    filtered = [seg for seg in segments if seg not in STOP_WORDS] or segments

    if filtered[-1] in GENERIC_SUFFIXES and len(filtered) >= 2:
        main = humanize_segment(filtered[-2])
        suffix = humanize_segment(filtered[-1])
        return f"{main} {suffix}".strip()

    return " ".join(humanize_segment(seg) for seg in filtered).strip()


def clean_value(raw: str) -> str:
    value = raw.rstrip()

    if value.endswith(SUFFIX):
        value = value[: -len(SUFFIX)].rstrip()

    if PLACEHOLDER_RE.fullmatch(value) and "." in value and " " not in value:
        value = placeholder_to_phrase(value)

    return value


def main() -> None:
    with TRANSLATION_FILE.open("r", encoding="utf-8") as fh:
        translations = json.load(fh, object_pairs_hook=OrderedDict)

    changes = 0
    for key, value in translations.items():
        if isinstance(value, str):
            cleaned = clean_value(value)
            if cleaned != value:
                translations[key] = cleaned
                changes += 1

    if changes:
        with TRANSLATION_FILE.open("w", encoding="utf-8") as fh:
            json.dump(translations, fh, ensure_ascii=False, separators=(",", ":"))

    print(f"Updated {changes} entries.")


if __name__ == "__main__":
    main()
