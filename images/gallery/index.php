<head>
  <title>jQuery Content Slider | Responsive jQuery Slider | bxSlider</title>
  <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="keywords" content="content slider, responsive image gallery, responsive image gallery, image slider, image fade, image rotator">
  <meta name="description" content="Respsonsive jQuery content slider.">
  <meta name="viewport" content="width=device-width, initial-scale=1"> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
</head>
<body>
<div class="bxslider">
  <div><img src="../3.png" style="width:100%"></div>
  <div><img src="../4.png" style="width:100%"></div>
  <div><img src="../5.png" style="width:auto"></div>
  <div><img src="../6.jpg" style="width:50%"></div>
  <div><img src="../7.png" style="width:50%"></div>
  <div><img src="../8.png" style="width:50%"></div>
  <div><img src="../9.jpg" style="width:100%"></div>

<br>
<a href="http://code.intergrid.cat/sources/metamodel/images/esquema-seny-2d.png">MODEL 2D</a><br>
<script>
$(function(){
  $('.bxslider').bxSlider({
    mode: 'fade',
    captions: true,
    slideWidth: 1600, 
    slideHeight: auto
  });
});
</script>
