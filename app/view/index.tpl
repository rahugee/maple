<html>
<head>
    <meta charset="utf-8">
    <script src="{$cdn_server}dist/bootloader_bundled/webmodules.bootloader.js">
        window.bootloader({
            debug: true,
            version: (new Date()).getTime(),
            appContext: '{$context_path}/',
            resourceDir: '{$context_path}',
            resourceUrl: '{$cdn_server}',
            resourceJson: "dist/resource.json",
            indexBundle: "maple/web2",
            debugBundles: [],
            CONST : {
                CONTEXT_PATH : '{$context_path}',
                COVER_PATH : '/src/cover',
                USER_LINK : "/user/",
                STORY_LINK : "/story/"
            }
        });
    </script>
</head>
<body>
</body>
</html>