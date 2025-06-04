<head>
    <link rel="stylesheet" href="./CSS/about.css">
</head>
<?php include './header.php'; ?>
<div class="page" id="about">
    <div class="content">
        <section class="about-welcome">
            <h2>Welcome to Zigma Hospital</h2>
        </section>

        <section class="about-description">
            <p>At Zigma Hospital, we are dedicated to providing compassionate, high-quality healthcare that puts patients first. With a team of experienced doctors, nurses, and healthcare professionals, we offer a comprehensive range of medical services in a safe, welcoming environment.</p>
            <p>Established in 2000, our hospital is equipped with modern medical technology and facilities to ensure accurate diagnosis, effective treatment, and continuous patient care. Whether it's emergency services, specialized treatments, or preventive care, our focus is on healing, comfort, and well-being.</p>
        </section>

        <div class="slideshow-container">
            <div class="slide fade">
                <img src="./images/HOSPITAL/im1.png" alt="Zigma Hospital Image 1">
            </div>
            <div class="slide fade">
                <img src="./images/HOSPITAL/im2.png" alt="Zigma Hospital Image 2">
            </div>
            <div class="slide fade">
                <img src="./images/HOSPITAL/im3.png" alt="Zigma Hospital Image 3">
            </div>
            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>
        </div>

        <section class="about-vision">
            <h3>Our Vision</h3>
            <p>To become the region’s most trusted healthcare provider, known for our commitment to excellence, innovation in medical care, and dedication to improving lives.</p>
        </section>

        <section class="about-mission">
            <h3>Our Mission</h3>
            <p>Our mission is to provide high-quality, patient-focused healthcare services that meet the needs of our community. We strive to foster a culture of continuous improvement, where compassion and professionalism guide every interaction. Through clinical excellence, advanced medical practices, and a commitment to holistic wellness, we aim to enhance the health and well-being of every individual we serve.</p>
        </section>

        <!-- Debug: Check if JavaScript is running -->
        <script>
            console.log("Slideshow script loaded. Initial slideIndex: " + slideIndex);
        </script>
    </div>
</div>
<?php include './footer.php'; ?>