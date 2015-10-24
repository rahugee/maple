<html>
<head>
    <title>{$WEBSITE_TITLE}</title>
    <meta charset="utf-8">
    <script src="{$CDN_SERVER}dist/bootloader_bundled/webmodules.bootloader.js">
        window.bootloader({
            debug: false,
            version: {$VERSION},
            appContext: '{$CONTEXT_PATH}',
            resourceDir: "", //'{$CONTEXT_PATH}',
            resourceUrl: '{$CDN_SERVER}',
            resourceJson: "dist/resource.json",
            indexBundle: "maple/web2",
            debugBundles: ["maple/web2"],
            CONST : {
                CONTEXT_PATH : '{$CONTEXT_PATH}',
                STATIC_SERVER : '{$STATIC_SERVER}',
                COVER_PATH : '{$STATIC_SERVER}/static/cover/',
                ACTOR_PIC_PATH : '{$STATIC_SERVER}/static/actors/',
                DP_PATH : "{$STATIC_SERVER}/static/pic_authors/",
                USER_LINK : "/user/",
                STORY_LINK : "/story/",
                COMMENTS_PAER_PAGE : 10
            }
        });
    </script>
</head>
<body>
</body>
</html>