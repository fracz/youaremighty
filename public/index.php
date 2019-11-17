<?php
$name = htmlspecialchars($_GET['name'] ?? '');
$defaultLang = strtolower(end(explode('.', $_SERVER['HTTP_HOST'])));
$lang = $_GET['lang'] ?? $defaultLang;
if (!file_exists(__DIR__ . '/texts/' . basename($lang) . '.json')) {
    $lang = 'en';
}
$translations = json_decode(file_get_contents(__DIR__ . '/texts/' . $lang . '.json'), true);
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#1e2327">
    <title><?= trim(str_replace('{name}', $name, $translations['pageTitle']), ', ') ?></title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>


<div id="credits" style="opacity: <?= $name ? 0 : 1 ?>">
    <div class="github-stars">
        <a href="https://github.com/fracz/youaremighty">
            <img alt="GitHub stars"
                 src="https://img.shields.io/github/stars/fracz/youaremighty?label=github&style=for-the-badge">
        </a>
    </div>

    <div class="footer">
        <p class="text-center text-muted small"> Made with ❤️ for web by Wojciech Frącz, based on <a
                    href="http://www.aninote.com">aninote.com</a>.
            <a href="https://github.com/fracz/youaremighty">More credits.</a></p>
    </div>
</div>

<div class="superman" id="superman">
    <div class="superman--bodyAndLegs">
        <div class="superman--symbol superman--symbol__red"></div>
        <div class="superman--symbol superman--symbol__yellow"></div>
    </div>
    <div class="superman--arm superman--arm__left"></div>
    <div class="superman--arm superman--arm__right"></div>
    <div class="superman--neck">
        <div class="superman--head">
            <div class="superman--mouth"></div>
            <div class="superman--eye superman--eye__left"></div>
            <div class="superman--eye superman--eye__right"></div>
        </div>
    </div>
    <div class="superman--cap"></div>
</div>

<div class="container">
    <div class="content" id="content">
        <div class="slideshow">
            <div class="slide" id="slide0">
                <div>
                    <?php if ($name): ?>
                        <h1><?= str_replace('{name}', $name, $translations['header']) ?></h1>
                        <a class="btn" onclick="play()" id="playButton">Play 🔊</a>

                    <?php else: ?>
                        <form action="" method="get" onsubmit="return goToMighty()">
                            <input type="text" placeholder="<?= $translations['yourName'] ?>" id="mightyName"
                                   autocapitalize="off"
                                   autocomplete="off" autofocus>
                            <div class="langs" id="langs">
                                <?php
                                $langs = scandir(__DIR__ . '/texts');
                                foreach ($langs as $langFile):
                                    if (!preg_match('/([a-z]+)\.json/', $langFile, $match)) {
                                        continue;
                                    }
                                    ?>
                                    <a class="btn" href="/?lang=<?= $match[1] ?>"
                                       id="lang-btn-<?= $match[1] ?>">
                                        <?= strtoupper($match[1]) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <button class="btn" type="submit"><?= $translations['create'] ?></button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    var audio = new Audio('/audio.mp3');
    audio.load();

    <?php if ($name): ?>

    var texts = <?=json_encode($translations['texts'])?>;
    texts[3] = '<div class="you"><?=$name?></div>' + texts[3];
    texts[21] = '<div class="you"><?=$name?></div>' + texts[21];

    var content = document.getElementById('content');
    var slideshow = document.createElement('div');
    slideshow.className = 'slideshow';

    var firstSlide = document.createElement('div');
    firstSlide.className = 'slide';
    firstSlide.id = 'slide0';
    firstSlide.innerHTML = '<div>' + document.getElementById('slide0').innerHTML + '</div>';
    slideshow.appendChild(firstSlide);

    for (var i = 0; i < texts.length; i++) {
        var slide = document.createElement('div');
        slide.classList.add('slide');
        slide.id = 'slide' + (i + 1);
        slide.innerHTML = '<div>' + texts[i] + '</div>';
        if (i >= 4) {
            var slideContent = slide.children[0];
            slideContent.style.transform = 'scale(6.0)';
            slideContent.classList.add('fast');
        }
        slideshow.appendChild(slide);
    }

    var currentVisibleSlide = 0;
    var playing = false;

    function play() {
        if (!playing) {
            playing = true;
            audio.play();
            startSlidesOnMusicStart();
            setTimeout(function () {
                document.getElementById('playButton').innerHTML = '<?=$translations['waitForIt']?>';
            }, 1000);
        }
    }

    function startSlidesOnMusicStart() {
        if (audio.currentTime > 0) {
            content.innerHTML = "";
            content.appendChild(slideshow);
            setTimeout(hideSlide(0));
            setTimeout(nextSlide, 3200);
        } else {
            setTimeout(startSlidesOnMusicStart, 100);
        }
    }

    var times = [
        // 2nd part delay, current text visible, next slide gap
        [0, 0, 3200],
        [900, 2750, 1800],
        [900, 2850, 2300],
        [900, 2600, 1700],
        [2200, 4400, 600],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 950, 250],
        [0, 1900, -100],
        [0, 1900, -100],
        [0, 1900, -100],
        [0, 1900, 500],
        [1000, 1000, 500],

    ];

    // var startFromSlide = 0;
    //
    // if (startFromSlide) {
    //     var timesSums = times.map((t) => t[1] + t[2]);
    //     var offset = 0;
    //     for (var j = 0; j < startFromSlide; j++) {
    //         offset += times[j][1] + times[j][2];
    //     }
    //     offset -= times[startFromSlide - 1][2];
    //     audio.currentTime = offset / 1000;
    //     currentVisibleSlide = startFromSlide - 1;
    // }

    function hideSlide(slideNumber) {
        return function () {
            var slide = slideshow.children[slideNumber].children[0];
            slide.style.opacity = '1';
            setTimeout(function () {
                slide.style.opacity = '0';
            });
            if (slideNumber === 4) {
                slide.style.transform = 'scale(8.0)';
                slide.getElementsByTagName('span')[0].style.opacity = '0';
                slide.getElementsByTagName('span')[0].style.transitionDuration = '0.1s !important';
            } else if (slideNumber > 4) {
                slide.style.transform = 'scale(0.5)';
            }
        }
    }

    function showSlide(slideNumber) {
        return function () {
            var slide = slideshow.children[slideNumber].children[0];
            // slide.style.display = 'block';
            slide.style.opacity = '0';
            setTimeout(function () {
                slide.style.opacity = '1';
                slide.style.transform = 'scale(1.0)';
            });
            var span = slide.getElementsByTagName('span')[0];
            if (span) {
                setTimeout(function () {
                    span.style.opacity = '1';
                }, times[slideNumber][0]);
            }
        }
    }

    function nextSlide() {
        ++currentVisibleSlide;
        var slideTimes = times[currentVisibleSlide];
        if (slideTimes) {
            setTimeout(showSlide(currentVisibleSlide));
            setTimeout(nextSlide, slideTimes[1] + slideTimes[2]);
            if (times[currentVisibleSlide + 1]) {
                setTimeout(hideSlide(currentVisibleSlide), slideTimes[1]);
            }
        } else {
            document.getElementById('superman').style.opacity = "1";
            setTimeout(function () {
                document.getElementById('credits').style.opacity = '1';
                document.getElementById('credits').style.zIndex = '100';
            }, 2000);
        }
    }

    document.body.addEventListener('keyup', function (e) {
        if (e.key === 'Enter') {
            play();
        }
    });

    <?php else: ?>
    var lang = '<?=$lang?>';

    function setLang(newLang) {
        document.getElementById('lang-btn-' + lang).classList.remove('active');
        lang = newLang;
        document.getElementById('lang-btn-' + lang).classList.add('active');
    }

    setLang(lang);

    function goToMighty() {
        var name = document.getElementById('mightyName').value;
        window.location.assign('/' + lang + '/' + encodeURIComponent(name || 'You'));
        return false;
    }

    <?php endif; ?>

</script>
</body>
</html>
