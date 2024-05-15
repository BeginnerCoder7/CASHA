<?php
session_start();

if (!isset($_SESSION['username']) && basename($_SERVER["PHP_SELF"]) != 'login.html') {
    header("Location: login.html");
    exit();
}


if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CASH-A | About Us</title>
    <style>
      body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: "Gill Sans", sans-serif;
      }

      .about-section {
        padding: 40px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color:#0d1f23;
      }

      .about-content {
        max-width: 800px;
        margin: auto;
        height:850px;
      }

      .title-with-image {
        display: flex;
        align-items: center;
        justify-content: center; 
        flex-direction: column; 
      }

      .left-image {
        width: 150px;
        margin-bottom: 20px;
      }

      .title-description {
        max-width: 600px;
      }

      .title-description h2 {
        font-size: 24px;
        margin-bottom: 10px;

      }

      .title-description p {
        font-size: 20px;
        margin-bottom: 20px;
        font-style: italic;

      }

      .read-more-btn {

        border: none;
        padding: 20px 50px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
        margin-top: 200px;
        font-size:18px;
        background-color:#e1dbca;
        color:#0d1f23;
        font-weight:bold;
      }
      .read-more-btn:hover {

      }

      .image-container img {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }


      @media only screen and (max-width: 768px) {
        .title-with-image {
          flex-direction: column;
        }
      }

      .about-section1,
      .about-section2,
      .about-section3 {
        overflow: hidden;
        padding: 20px;
      }

      h2 {
        font-size: 32px; 
      }

      p {
        font-size: 16px;
        line-height: 1.5;
        max-width: 600px; 
        margin: 0 auto; 
      }

      .about-section1 {
        background-color: #f1dcd0;
        color: #0d1f23;
      }

      .about-section2 {
        background-color: #0d1f23;
        color: white;
      }

      .about-section3 {
        background-color: #afb3b7;
        color: #0d1f23;
      }

      .image-container1,
      .image-container2 {
        text-align: center;
        margin-bottom: 20px; 
      }

      .image-container1 img,
      .image-container2 img {
        max-width: 100%;
        height: auto;
        display: block;
        width: 400px;
        border-radius: 10px;
      }


      @media only screen and (min-width: 769px) {
        .about-section1,
        .about-section2,
        .about-section3 {
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
        }

        .image-container1,
        .image-container2 {
          flex: 1 0 50%;
          max-width: 50%;
          margin-bottom: 0;
        }

        .image-container1 img{
          margin:0px 0px 0px 300px;
        }

        .text {
          margin: 150px auto;
        }
      }

      .return-image {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 999;
        width: 30px;
        cursor: pointer;
        margin:5px;
        background-color:#c1e8ff;
        padding:10px;
        border-radius:100px;
      }
      footer {
                background-color: black;
                color: white;
                text-align: center;
                padding: 10px 0;
                bottom: 0;
                width: 100%;
                font-size: 10px;
                font-weight: bold;
                bottom:0;
                z-index: 999;
            }
    </style>
  </head>
<body>
  <a href="base.php"><img src="return.png" class="return-image" alt="Return to base"></a>
      <div class="about-section black-bg">
        <div class="about-content">
          <div class="title-with-image"><br><br><br><br>
            <img src="casha.png" alt="CASHA Image" class="left-image" style="width:300px; height:300px; ">
            <div class="title-description">
              <p style="color:#c1e8ff;">Empowering dreams, enriching lives. Together, let's shape a brighter financial future</p>
            </div>
          </div>
          <button class="read-more-btn" onclick="scrollToParagraph()">Read More</button>
        </div>
      </div>
      <div class="about-section1">
        <div class="image-container1">
          <img src="aimage3.jpg" alt="CASHA Image">
        </div>
        <div class="text">
        <h2>Vision</h2><br>
        <p>Our vision is to create a world where financial freedom is within reach for everyone. We're passionate about empowering individuals and businesses to take control of their finances and unlock their full potential. By leveraging advanced technologies and a deep understanding of financial needs, we're reshaping the landscape of banking and money management. With a relentless pursuit of excellence and a dedication to customer satisfaction, we're paving the way for a future where financial aspirations are realized. Join us as we embark on this transformative journey together.</p>
        </div>
      </div>
      <div class="about-section2">
      <div class="text">
        <h2>Approach</h2><br>
        <p>Our approach is rooted in understanding the unique needs and goals of each individual and business we serve. We believe in tailor-made solutions that prioritize simplicity, security, and efficiency. By combining human expertise with advanced technology, we provide personalized financial services that empower our customers to make informed decisions and achieve their objectives. With a collaborative and client-centric approach, we're committed to delivering innovative solutions that exceed expectations and foster long-term success.</p>
      </div>  
        <div class="image-container2">
          <img src="aimage1.jpg" alt="CASHA Image" >
        </div>
        </div>
      <div class="about-section3">
        <div class="image-container1">
          <img src="portrait.jpg" alt="CASHA Image">
        </div>
        <div class="text">
        <h2>Approach</h2><br>
        <p>Our approach is rooted in understanding the unique needs and goals of each individual and business we serve. We believe in tailor-made solutions that prioritize simplicity, security, and efficiency. By combining human expertise with advanced technology, we provide personalized financial services that empower our customers to make informed decisions and achieve their objectives. With a collaborative and client-centric approach, we're committed to delivering innovative solutions that exceed expectations and foster long-term success.</p>
      </div>  
        </div>
    <footer>
    S.Y 2023-2024 ARELLANO UNIVERSITY ELISA ESGUERRA CAMPUS | CASHA
    </footer>

    <script>
      document.querySelector('.return-image').addEventListener('click', function() {
        window.location.href = 'base.php';
    });

      function scrollToParagraph() {
        const visionSection = document.querySelector('.about-section1');
        const visionSectionPosition = visionSection.offsetTop;

        window.scrollTo({
          top: visionSectionPosition,
          behavior: 'smooth'
        });
      }
    </script>
  </body>
</html>
