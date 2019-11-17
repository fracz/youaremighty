<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#1e2327">
    <title>Olimp, You Are Mighty!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="content" id="content">
        <div class="welcome" id="welcome">
            <h1>Hi Olimp!</h1>
            <h3>Are you ready?</h3>
            <button onclick="play()">play</button>
        </div>
    </div>
</div>

<script>
    var audio = new Audio('./audio.mp3');

    var texts = <?=file_get_contents(__DIR__ . '/texts/en.json')?>;
    texts[3] = '<div class="you">Olimp</div>' + texts[3];

    var content = document.getElementById('content');
    var slideshow = document.createElement('div');
    slideshow.className = 'slideshow';

    var firstSlide = document.createElement('div');
    firstSlide.className = 'slide';
    firstSlide.innerHTML = document.getElementById('welcome').innerHTML;
    slideshow.appendChild(firstSlide);

    for (var i = 0; i < texts.length; i++) {
        var slide = document.createElement('div');
        slide.classList.add('slide');
        slide.classList.add('slide' + i);
        slide.innerHTML = texts[i];
        slideshow.appendChild(slide);
    }

    var currentVisibleSlide = 14;

    function play() {
        content.innerHTML = "";
        content.appendChild(slideshow);
        audio.play();
        nextSlide();
    }

    var times = [
        // 2nd part delay, current text visible, next slide gap
        [0, 0, 3200],
        [900, 2750, 1700],
        [900, 2850, 2300],
        [900, 2600, 1700],
        [2200, 4400, 700],
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
        [0, 950, -500],
        [0, 2050, -500],
        [0, 1250, -500],
        [0, 1250, -500],

    ];

    var startFromSlide = 15;

    if (startFromSlide) {
        var timesSums = times.map((t) => t[1] + t[2]);
        var offset = 0;
        for (var j = 0; j < startFromSlide; j++) {
            offset += times[j][1] + times[j][2];
        }
        offset -= times[startFromSlide - 1][2];
        audio.currentTime = offset / 1000;
        currentVisibleSlide = startFromSlide - 1;
    }

    function nextSlide() {
        slideshow.children[currentVisibleSlide].style.opacity = '1';
        slideshow.children[currentVisibleSlide].style.display = 'block';
        setTimeout(() => slideshow.children[currentVisibleSlide].style.opacity = '0');
        if (currentVisibleSlide === 4) {
            slideshow.children[currentVisibleSlide].style.transform = 'scale(8.0)';
        }
        else if (currentVisibleSlide > 4) {
            slideshow.children[currentVisibleSlide].style.transform = 'scale(0.5)';
        }
        setTimeout(function () {
            slideshow.children[currentVisibleSlide].style.display = 'none';
            ++currentVisibleSlide;
            var currentSlide = slideshow.children[currentVisibleSlide];
            currentSlide.style.display = 'block';
            currentSlide.style.opacity = '0';
            if (currentVisibleSlide >= 5) {
                slideshow.children[currentVisibleSlide].style.transform = 'scale(6.0)';
                slideshow.children[currentVisibleSlide].classList.add('fast');
            }
            setTimeout(() => currentSlide.style.opacity = '1', 20);
            setTimeout(() => currentSlide.style.transform = 'scale(1.0)', 20);
            var span = currentSlide.getElementsByTagName('span')[0];
            if (span) {
                setTimeout(function () {
                    span.style.opacity = '1';
                }, times[currentVisibleSlide][0]);
            }
        }, times[currentVisibleSlide][2]);
        setTimeout(nextSlide, times[currentVisibleSlide][2] + times[currentVisibleSlide + 1][1]);
    }

</script>
</body>
</html>
