#!/usr/bin/env python3
import sys, re
body = sys.stdin.read()
for pattern, label in [
    (r'"message":"(.*?)"', 'MESSAGE'),
    (r'"class":"([^"]+Exception[^"]*|[^"]+Error[^"]*)"', 'CLASS'),
    (r'"title":"([^"]+)"', 'TITLE'),
    (r'SQLSTATE[^\\"]{0,200}', 'SQL'),
]:
    m = re.search(pattern, body)
    if m:
        print(label + ':', m.group(1) if m.lastindex else m.group(0))
