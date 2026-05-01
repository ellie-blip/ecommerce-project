
<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>Document</title>
</head>
 <style>
   
    body { 
            background-color: #8a9aaa;
            font-family: sans-serif;
        }
      
    .carousel-container {
            margin-top: 100px; 
            perspective: 1000px;     
            perspective: 1200px; 
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 1200px;
        }
    .carousel-fan {
            position: relative;
            width: 300px;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            transform-style: preserve-3d;
        }
       
     .item {
            position: absolute;
            width: 280px;
            height: 380px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            cursor: pointer;
           
            transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1); 
        }

     .item.active {
            transform: translateZ(200px); 
            z-index: 10;
            opacity: 1;
        }

    .item.left {
            transform: translateX(-250px) translateZ(-150px) rotateY(30deg);
            z-index: 5;
            opacity: 0.6;
        }

   .item.right {
            transform: translateX(250px) translateZ(-150px) rotateY(-30deg);
            z-index: 5;
            opacity: 0.6;
        }

    .item.hidden {
            transform: translateZ(-500px);
            opacity: 0;
            z-index: 1;
        }
    .start {
        width: 30%;
        padding: 10px 30px;
        background-color:  #03033d;
        color: #f1f3f8;
        border: none;
        font-size: 20px;
        font-weight: bold;
        letter-spacing: 1px;
        cursor: pointer;
        font-family: Georgia, 'Times New Roman', serif;
        display: block;
        margin-top:40px;
        margin-left: auto;
        margin-right: auto;
        width: 60px; 
        font-family: Georgia, 'Times New Roman', serif;
    }

    .start:hover{
        color:  #468ad8;
        font-weight: bold;
        font-size: 20px;
    }
    .s {
        outline-width: 100px;
        color: #f3f0eb;
        text-decoration: none;
        font-size: 50px;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase ;
        white-space: nowrap;
        margin-top:30px;
        font-family: Georgia, 'Times New Roman', serif;
    }
   .g{
        margin-top: 100px;
   }
    </style>
</head>
<body>
<h1 class="s" align="center">bienvenue à Maison Élégance  </h1>
    <h2 align="center">  Votre prochaine voiture vous attend ici ! </h2>
      <p align="center">  Nous ne vendons pas seulement des voitures - nous réalisons votre passion avec qualité, 
transparence et des garanties qui vous assurent 
une tranquillité totale dès le premier jour. </p>
        
    <div class="carousel-container">
        <div class="carousel-fan" id="carouselFan">
                <img src="ph/photo1.jpg" class="item left">
                <img src="ph/photo2.jpg" class="item left">
                <img src="ph/photo3.jpg" class="item left">
                <img src="ph/photo4.jpg" class="item active">
                <img src="ph/photo5.jpg" class="item right">
                <img src="ph/photo6.jpg" class="item right">
                <img src="ph/photo7.jpg" class="item right">
                <img src="ph/photo8.jpg" class="item right">
        </div>
    </div>
    <div>
         <h2 align="center" class="g">  < Parcourez notre collection dès maintenant et commencez votre voyage > </h2>
    </div>
    <div>
     <a class="start" href="index.php" > start </a>
   
     </div>
    <script>
        const items = document.querySelectorAll('.item');

        items.forEach((item, index) => {
           
            item.addEventListener('click', () => {
                moveCarousel(index);
            });
        });

        function moveCarousel(clickedIndex) {
            items.forEach((item, index) => {
              
                item.classList.remove('active', 'left', 'right', 'hidden');

                if (index === clickedIndex) {
                    item.classList.add('active'); 
                } else if (index === (clickedIndex - 1 + items.length) % items.length) {
                    item.classList.add('left'); 
                } else {
                    item.classList.add('right'); 
                }
            });
        }
    </script>

</body>
</html>