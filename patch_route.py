import codecs

def patch_web_route():
    with codecs.open('routes/web.php', 'r', 'utf-8') as f:
        text = f.read()

    # Find the target mapping array in packages/{slug}
    target_str = "'keywords'   => json_decode($dbPkg->keywords, true) ?: [],"
    if target_str in text:
        text = text.replace(
            target_str,
            target_str + "\n            'editorial_itinerary' => property_exists($dbPkg, 'editorial_itinerary') ? $dbPkg->editorial_itinerary : null,"
        )
        print("Successfully added editorial_itinerary to web.php route mapping!")
    else:
        print("Target string not found in web.php")

    with codecs.open('routes/web.php', 'w', 'utf-8') as f:
        f.write(text)

patch_web_route()
