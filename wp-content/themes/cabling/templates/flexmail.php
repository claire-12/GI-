<?php
/**
 *  Template Name: Flexmail Test
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style id="flx-styles">
        body {
            font-family: -apple-system, BlinkMacSystemFont, "SegoeUI", Arial, sans-serif;
            font-size: 1em;
            line-height: 1.5;
            overflow-y: scroll;
            min-height: 100%;
            text-rendering: optimizeLegibility;
            -webkit-font-feature-settings: 'kern';
            font-feature-settings: 'kern';
            -webkit-font-smoothing: antialiased;
            -webkit-overflow-scrolling: touch;
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            padding-top: 0;
            background-color: #f9f9f9;
        }

        h3 {
            font-weight: bold;
            font-size: 18px;
            color: #333333;
        }

        p {
            margin-bottom: 12px;
            margin-top: 12px;
        }

        table {
            position: relative;
            color: #434343;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        a {
            color: #2C00AC;
            text-decoration: none;
        }

        a:hover {
            color: #2C00AC;
            text-decoration: underline;
        }

        input, button, select, textarea {
            font-size: 13px !important;
        }

        p.small {
            color: #999999;
            font-size: 10px;
            margin: 0;
            text-align: right;
        }

        /* custom code */
        /*
        #btn-send {
          background-color: #666666 !important;
          border: 0 !important;
          color: #FBFEEE !important;
          background-image: none !important;
          border-radius: 0 !important;
        }

        * {
            font-family: Verdana !important;
        }

        input {
            background-color: #EEEEEE !important;
            color: #333333 !important;
        }
        */

    </style>
    <script>
        const fm = function () {
            return {
                load: function () {
                    const e = document, t = e.getElementById("iframe_flxml_form"), n = e.getElementById("flx-styles"),
                        r = n ? n.innerHTML : "";
                    t ? t.contentWindow.postMessage(r, "https://www.flexmail.eu/") : alert("Flexmail: Frame not found!")
                }
            }
        }();
    </script>
    <title></title>
</head>
<body>
<h3>Newsletter subscription</h3>
<p>
    Here you have the opportunity to sign up for the Datwyler newsletter. <br/>
    Stay informed and get the latest news from the world of Datwyler – conveniently via email.</p>
<iframe id="iframe_flxml_form" onload="javascript: fm.load();"
        src="https://www.flexmail.eu/sf-4114676852c430cae647d7a6858ae3043efb0" frameborder="0" scrolling="no"
        style="overflow:hidden;height:418px;width:100%" height="100%" width="100%"></iframe>
<p class='small'>
    <a href='https://flexmail.be' target='_blank'>Powered by Flexmail </a>
</p>

</body>
</html>
