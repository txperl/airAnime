import json

with open("./data/py/bimibimi.json", "r", encoding="utf-8") as f:
    data = json.load(f)

r = []
for d in data:
    r.append({"title": data[d], "link": "http://www.bimiacg4.net/bangumi/bi/" + d})

with open("./data/bimibimi.json", "w", encoding="utf-8") as f:
    json.dump(r, f)
