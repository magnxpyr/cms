{%
do assets.addInlineJs('
    <script type="text/javascript" charset="utf-8">
        $().ready(function() {
            $("#elfinder").elfinder({
            lang: "en",             // language (OPTIONAL)
            url : "/vendor/elFinder/php/connector.minimal.php"   // {{ connector }} connector URL (REQUIRED)
            }).elfinder("instance");
        });
    </script>
') %}

<div id="elfinder"></div>