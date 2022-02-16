<!--
Code share bởi sharescript.net
Vào ngay sharescript.net để tải nhiều source miễn phí và chất lượng
-->
<!--
Note: Trong chế độ demo, js sẽ bị mã hóa. Để tải source đầy đủ không mã hóa, vui lòng download ở trang chủ!
-->
<meta charset=UTF-8">
<title>Hệ thống đang bảo trì</title>
<link rel="shortcut icon" href="{{ asset('image/favicon.png') }}">
<style type="text/css">
    body {
        background-image: url({{ asset('image/ss-countdown-main-bg.png') }});
    }

    .ss-countdown-container .ss-bg-layer {
        background-image: url({{ asset('image/ss-countdown-bg.png') }});
        background-repeat: no-repeat;
        position: relative;
    }
</style>
<script>
    function reloadPage(){
        @if($setting['baotri'] == 0)
            window.location.href = "{{ route('home') }}";
        @endif
    }
    reloadPage();
</script>
<div class="ss-countdown-container">
<img src="https://vipmomo.club/public/image/vipmomo.png" style="margin-top:10px; margin-bottom:10px; width: 210px;" alt=" Logo"> 
    <h1>Website Chẵn Lẻ Autocltxmomo.CLUB đang bảo trì! để nâng cấp hệ thống</h1>
    <h4>Bạn có thể quay trở lại sau:</h4>
    <div class="ss-clock-container">
        <!--<div class="ss-clock-days-container">-->
        <!--    <div class="ss-bg-layer">-->
        <!--        <canvas class="ss-days-canvas" width="216" height="216"></canvas>-->
        <!--        <div class="ss-text"><p class="ss-value">0</p>-->
        <!--            <p class="ss-days-text">Ngày</p></div>-->
        <!--    </div>-->
        <!--</div>-->

        <div class="ss-clock-hours-container">
            <div class="ss-bg-layer">
                <canvas class="ss-hours-canvas" width="216" height="216"></canvas>
                <div class="ss-text"><p class="ss-value">0</p>
                    <p class="ss-hours-text">Giờ</p></div>
            </div>
        </div>

        <div class="ss-clock-minutes-container">
            <div class="ss-bg-layer">
                <canvas class="ss-minutes-canvas" width="216" height="216"></canvas>
                <div class="ss-text"><p class="ss-value">0</p>
                    <p class="ss-minutes-text">Phút</p></div>
            </div>
        </div>

        <div class="ss-clock-seconds-container">
            <div class="ss-bg-layer">
                <canvas class="ss-seconds-canvas" width="216" height="216"></canvas>
                <div class="ss-text"><p class="ss-value">0</p>
                    <p class="ss-seconds-text">Giây</p></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">


    (function () {
        "use strict";
        var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor),

            loadCssHack = function (url, callback) {
                var link = document.createElement('link');
                link.type = 'text/css';
                link.rel = 'stylesheet';
                link.href = url;

                document.getElementsByTagName('head')[0].appendChild(link);

                var img = document.createElement('img');
                img.onerror = function () {
                    if (callback && typeof callback === "function") {
                        callback();
                    }
                };
                img.src = url;
            },
            loadRemote = function (url, type, callback) {
                if (type === "css" && isSafari) {
                    loadCssHack(url, callback);
                    return;
                }
                var _element, _type, _attr, scr, s, element;

                switch (type) {
                    case 'css':
                        _element = "link";
                        _type = "text/css";
                        _attr = "href";
                        break;
                    case 'js':
                        _element = "script";
                        _type = "text/javascript";
                        _attr = "src";
                        break;
                }

                scr = document.getElementsByTagName(_element);
                s = scr[scr.length - 1];

                if (typeof s == "undefined") {
                    scr = document.getElementsByTagName("script");
                    s = scr[scr.length - 1];
                }

                element = document.createElement(_element);
                element.type = _type;
                if (type == "css") {
                    element.rel = "stylesheet";
                }
                if (element.readyState) {
                    element.onreadystatechange = function () {
                        if (element.readyState == "loaded" || element.readyState == "complete") {
                            element.onreadystatechange = null;
                            if (callback && typeof callback === "function") {
                                callback();
                            }
                        }
                    };
                } else {
                    element.onload = function () {
                        if (callback && typeof callback === "function") {
                            callback();
                        }
                    };
                }
                element[_attr] = url;
                s.parentNode.insertBefore(element, s.nextSibling);
            },
            loadScript = function (url, callback) {
                loadRemote(url, "js", callback);
            },
            loadCss = function (url, callback) {
                loadRemote(url, "css", callback);
            };
        loadScript("{{ asset('js/jquery-1.11.1.min.js') }}", function () {
            loadScript("{{ asset('js/ss_options.js?time=666') }}", function () {
                loadScript("{{ asset('js/ss_countdown.js') }}", function () {
                    loadCss("{{ asset('css/ss_countdown.css') }}", function () {
                        new SSCountdown(ssCountdownDefaultOptions);
                    });
                });
            });
        });
    })();
</script>
<!--
Note: Trong chế độ demo, js sẽ bị mã hóa. Để tải source đầy đủ không mã hóa, vui lòng download ở trang chủ!
-->
