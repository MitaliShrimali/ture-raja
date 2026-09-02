import urllib.request, re
try:
    html = urllib.request.urlopen('https://tour-raja.com/').read().decode('utf-8')
    print(re.findall(r'<img[^>]*alt="Tour Raja"[^>]*>', html))
except Exception as e:
    print(e)
