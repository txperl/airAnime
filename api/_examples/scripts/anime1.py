import requests
import re
from zhconv import convert
import json

playlist_url = "https://d1zquzjgwo9yb.cloudfront.net/"
playlist_data = requests.get(playlist_url).json()

r = []

for item in playlist_data:
    link = item[0]
    title = convert(item[1], "zh-cn")
    # Format title
    result = re.search(r"<a[^>]*>(.*?)</a>", title)
    if result:
        title = result.group(1)
    else:
        title = title
    r.append({"title": title, "link": link})

with open("../data/anime1.json", "w", encoding="utf-8") as f:
    json.dump(r, f, ensure_ascii=False)
