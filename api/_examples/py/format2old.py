import json

with open("./api/_examples/py/bimibimi.json", "r", encoding="utf-8") as f:
    data = json.load(f)

r = []
for d in data:
    title = (
        data[d]
        .replace("【无修版】", "")
        .replace("【无修正】", "")
        .replace("无修版", "")
        .replace("无修正", "")
        .replace("【无暗牧】", "")
        .strip()
    )
    r.append({"title": title, "link": "http://www.bimiacg10.net/bangumi/bi/" + d})

with open("./api/_examples/data/bimibimi.json", "w", encoding="utf-8") as f:
    json.dump(r, f, ensure_ascii=False)
