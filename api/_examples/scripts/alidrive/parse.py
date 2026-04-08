"""Parse raw.docx (alipan anime list) into alid.json + alid_ruach.json.

Output (next to this script):
  alid.json        — [{title, link: full URL}]
  alid_ruach.json  — [{title, link: share ID}]
  dups.txt         — duplicate audit report
"""

import html
import json
import os
import re
import sys
import zipfile
from collections import Counter, OrderedDict

HERE = os.path.dirname(os.path.abspath(__file__))
DOCX = os.path.join(HERE, "raw.docx")
OUT_RAW = os.path.join(HERE, "alid.json")
OUT_PURE = os.path.join(HERE, "alid_ruach.json")
OUT_DUPS = os.path.join(HERE, "dups.txt")

PARA_RE = re.compile(r"<w:p\b[^>]*>.*?</w:p>", re.S)
TEXT_RE = re.compile(r"<w:t[^>]*>([^<]*)</w:t>")
HYPERLINK_RE = re.compile(r"<w:instrText[^>]*>\s*HYPERLINK\s+(https?://\S+)")
ALI_RE = re.compile(r"^https?://(?:www\.)?(?:alipan|aliyundrive)\.com/s/([A-Za-z0-9]+)")
TITLE_STRIP = "✨⭐● \t"


def load_paragraphs():
    with zipfile.ZipFile(DOCX) as z:
        xml = z.read("word/document.xml").decode("utf-8")
    return PARA_RE.findall(xml)


def para_info(p):
    text = "".join(TEXT_RE.findall(p)).strip()
    ali = []
    for url in HYPERLINK_RE.findall(p):
        m = ALI_RE.match(url)
        if m:
            ali.append((url, m.group(1)))
    return text, ali


def clean_title(t):
    return html.unescape(t).lstrip(TITLE_STRIP).strip()


def parse():
    paragraphs = load_paragraphs()
    records = []  # list of (title, full_url, share_id)
    counts = {"A": 0, "B": 0, "C": 0, "skip": 0, "B_mismatch": 0}
    prev_text = None

    for p in paragraphs:
        text, ali = para_info(p)

        if not ali:
            prev_text = text if text else None
            continue

        if len(ali) == 1:
            full, sid = ali[0]
            if text == full:
                # C: legacy — title is in the previous paragraph (✨...)
                if prev_text and prev_text.startswith("✨"):
                    title = clean_title(prev_text)
                    if title:
                        records.append((title, full, sid))
                        counts["C"] += 1
                    else:
                        counts["skip"] += 1
                else:
                    counts["skip"] += 1
            else:
                # A: title and link in same paragraph
                title = clean_title(text)
                if title:
                    records.append((title, full, sid))
                    counts["A"] += 1
                else:
                    counts["skip"] += 1
        else:
            # B: multiple titles separated by 2+ spaces, paired with multiple links
            titles = re.split(r"\s{2,}", text)
            if len(titles) == len(ali):
                for ti, (full, sid) in zip(titles, ali):
                    ti = clean_title(ti)
                    if ti:
                        records.append((ti, full, sid))
                        counts["B"] += 1
                    else:
                        counts["skip"] += 1
            else:
                counts["B_mismatch"] += 1
                counts["skip"] += 1
                print(
                    f"  warn: B-class title/url count mismatch: text={text!r} urls={[u for u,_ in ali]}",
                    file=sys.stderr,
                )

        prev_text = None

    return records, counts


def dedupe(records):
    """Drop rows where (title, share_id) repeats; keep first occurrence."""
    seen = set()
    out = []
    dropped = []
    for r in records:
        key = (r[0], r[2])
        if key in seen:
            dropped.append(r)
            continue
        seen.add(key)
        out.append(r)
    return out, dropped


def write_dups_report(records, dropped):
    """Write a human-readable duplicate report."""
    # Index by title and by share_id (over the deduped set)
    title_to_sids = OrderedDict()
    sid_to_titles = OrderedDict()
    for ti, _full, sid in records:
        title_to_sids.setdefault(ti, []).append(sid)
        sid_to_titles.setdefault(sid, []).append(ti)

    # Group dropped by (title, sid)
    dropped_groups = Counter((r[0], r[2]) for r in dropped)

    lines = []

    lines.append("=" * 70)
    lines.append("[1] DROPPED — exact duplicates (same title + same share_id)")
    lines.append("=" * 70)
    if not dropped_groups:
        lines.append("(none)")
    else:
        for (ti, sid), n in sorted(dropped_groups.items(), key=lambda x: -x[1]):
            # +1 because dropped only counts the *extra* occurrences
            lines.append(f"  {n + 1}x  {ti}  |  {sid}")
    lines.append("")

    lines.append("=" * 70)
    lines.append("[2] KEPT — same title, different share_ids (likely diff resources)")
    lines.append("=" * 70)
    multi = [(t, s) for t, s in title_to_sids.items() if len(set(s)) > 1]
    if not multi:
        lines.append("(none)")
    else:
        for ti, sids in multi:
            lines.append(f"  {ti}  ->  {sids}")
    lines.append("")

    lines.append("=" * 70)
    lines.append("[3] KEPT — same share_id, different titles (alias / bundled)")
    lines.append("=" * 70)
    multi_sid = [(s, t) for s, t in sid_to_titles.items() if len(set(t)) > 1]
    if not multi_sid:
        lines.append("(none)")
    else:
        for sid, titles in multi_sid:
            lines.append(f"  {sid}  ->  {titles}")
    lines.append("")

    with open(OUT_DUPS, "w", encoding="utf-8") as f:
        f.write("\n".join(lines))

    return len(dropped_groups), len(multi), len(multi_sid)


def main():
    records, counts = parse()
    raw_total = len(records)

    deduped, dropped = dedupe(records)
    final_total = len(deduped)

    raw_json = [{"title": t, "link": full} for (t, full, _sid) in deduped]
    pure_json = [{"title": t, "link": sid} for (t, _full, sid) in deduped]

    with open(OUT_RAW, "w", encoding="utf-8") as f:
        json.dump(raw_json, f, ensure_ascii=False)
    with open(OUT_PURE, "w", encoding="utf-8") as f:
        json.dump(pure_json, f, ensure_ascii=False)

    n_exact, n_same_title, n_same_sid = write_dups_report(deduped, dropped)

    print(f"A (same-paragraph)  : {counts['A']}")
    print(f"B (multi in 1 para) : {counts['B']}")
    print(f"C (legacy two-para) : {counts['C']}")
    print(f"skipped paragraphs  : {counts['skip']} (B mismatch: {counts['B_mismatch']})")
    print(f"raw records         : {raw_total}")
    print(f"dropped duplicates  : {len(dropped)} ({n_exact} groups)")
    print(f"final records       : {final_total}")
    print(f"  same title / diff share_id : {n_same_title} groups (kept)")
    print(f"  same share_id / diff title : {n_same_sid} groups (kept)")
    print(f"wrote: {OUT_RAW}")
    print(f"wrote: {OUT_PURE}")
    print(f"wrote: {OUT_DUPS}")


if __name__ == "__main__":
    main()
