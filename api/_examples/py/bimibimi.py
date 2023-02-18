from concurrent.futures import ThreadPoolExecutor, as_completed

import re
import os
import json
import time
import requests


class Bimiacg(object):
    ROOT_URL = "http://www.bimiacg4.net/bangumi/bi/{index:d}/"
    HEADERS = {
        "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:107.0) Gecko/20100101 Firefox/107.0",
        "Referer": ROOT_URL,
    }
    REGS = [
        r'1class="current" title="(.*?)" href="\/bangumi\/bi\/(\d*)\/"',
        r'0<a href="\/bangumi\/bi\/(\d*)\/" title="(.*?)"',
    ]

    @classmethod
    def get(cls, index: int, is_format: bool = True) -> dict or str:
        print("[Bimiacg] gettting", index)
        try:
            rep = requests.get(
                cls.ROOT_URL.format(index=index), headers=cls.HEADERS
            ).text
        except:
            print("[Bimiacg] error: Bimiacg get", index)
            return {} if is_format else ""
        if not is_format:
            return rep
        r = cls.format(rep)
        if len(r) == 0:
            print(f"[Bimiacg] no infos", index)
        return r

    @classmethod
    def format(cls, raw: str) -> dict:
        r = {}
        for reg in cls.REGS:
            iid = int(reg[0])
            freg = reg[1:]
            matches = re.finditer(freg, raw, re.MULTILINE)
            for item in matches:
                r.update({item.group(iid + 1): item.group(2 - iid)})
        return r


if __name__ == "__main__":
    # config
    begin, end = (830, 850)
    bundle = 10
    filepath = "./api/_examples/py/bimibimi.json"

    # start
    pool = ThreadPoolExecutor(max_workers=10)
    r = {}
    if os.path.exists(filepath):
        with open(filepath, "r", encoding="utf-8") as f:
            r = json.load(f)
    status = []
    for page in range(begin, end):
        for index in range(page * bundle, (page + 1) * bundle):
            if str(index) not in r:
                status.append(pool.submit(Bimiacg.get, index=index, is_format=True))
            else:
                print("[Bimiacg] skip", index)
        for x in as_completed(status):
            r.update(x.result())
        print("[Bimiacg] writing")
        with open(filepath, "w", encoding="utf-8") as f:
            json.dump(r, f, ensure_ascii=False)
        time.sleep(2)
    print("[Bimiacg] done")
