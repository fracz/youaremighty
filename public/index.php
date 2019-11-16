<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
        slide.className = 'slide';
        slide.innerHTML = texts[i];
        slideshow.appendChild(slide);
    }

    var currentVisibleSlide = 0;

    function play() {
        content.innerHTML = "";
        content.appendChild(slideshow);
        audio.play();
        nextSlide();
    }

    function nextSlide() {
        slideshow.children[currentVisibleSlide].style.opacity = '1';
        slideshow.children[currentVisibleSlide].style.display = 'block';
        setTimeout(() => slideshow.children[currentVisibleSlide].style.opacity = '0');
        setTimeout(function () {
            slideshow.children[currentVisibleSlide].style.display = 'none';
            ++currentVisibleSlide;
            var currentSlide = slideshow.children[currentVisibleSlide];
            currentSlide.style.display = 'block';
            currentSlide.style.opacity = '0';
            setTimeout(() => currentSlide.style.opacity = '1', 20);
            var span = currentSlide.getElementsByTagName('span')[0];
            if (span) {
                setTimeout(function () {
                    console.log("aa");
                    span.style.opacity = '1';
                }, 1000);
            }
            setTimeout(nextSlide, 3000);
        }, 1000);
    }

</script>
</body>
</html>
