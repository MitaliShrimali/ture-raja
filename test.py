import urllib.request, re
try:
    html = urllib.request.urlopen('https://tour-raja.com/agent/login').read().decode('utf-8', errors='ignore')
    print("Agent Login image tags:")
    print(re.findall(r'<img[^>]*Tour Raja[^>]*>', html))
except Exception as e:
    print(e)
