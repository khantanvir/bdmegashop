<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Mail From Job Applicant</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      font-size: 14px;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    h2 {
      color: #ff6600;
      margin-top: 0;
    }
    p {
      margin-bottom: 10px;
    }
    .button {
      display: inline-block;
      background-color: #ff6600;
      color: #ffffff;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
    }
    .logo {
      max-width: 150px;
      display: block;
      margin-bottom: 20px;
    }
    .icon {
      display: inline-block;
      vertical-align: middle;
      margin-right: 5px;
    }
	.spn-from {
		background-color: #1fe3a1;
		color: white;
		padding: 3px;
		border-radius: 7%;
	}
	.spn-to {
		background-color: #707fe9;
		color: white;
		padding: 3px;
		border-radius: 7%;
	}
  </style>
</head>
<body>
  <div class="container">
    <h2>{{ (!empty($details['subject_for_user']))?$details['subject_for_user']:'New Job Application' }}</h2>
    <p>Dear, {{ (!empty($details['name']))?$details['name']:'' }}<br>

    <p>
        <div class="card px-3 pt-3">
            <section class="main-body px-3 pb-3">
                <p>Thank you for showing interest in <strong>UK Management College.</strong></p>
                <p>Thank you for applying! Your application has been received. Our team will review your information and contact you if your qualifications match our requirements.
We appreciate your interest in joining us.</p>
            </section>
        </div>
    </p>
    <p>Thank you.</p>

    <p><b>Best regards,</b><br>
      HR Team<br>
      UK Management College<br>
      College House<br>
      Stanley Street<br>
      Openshaw<br>
      M11 1LE
    </p>
    <img src="https://ukmc.ac.uk/img/home/logo_ukmc.png" alt="UKMC Logo" class="logo">
    <p>
        Email: <a href="mailto:hr@ukmc.ac.uk">hr@ukmc.ac.uk</a><br>
        Tel: <a href="tel:+4401614780015">+4401614780015</a><br>
        Web: <a target="_blank" href="https://ukmc.ac.uk/">https://ukmc.ac.uk/</a>
    </p>

    <small><em>Please note: The information contained in this email is intended only for the person or entity to which it is addressed and may contain confidential and/or privileged material. If you are not the intended recipient, any use, disclosure, copying, or distribution of this information is strictly prohibited. If you have received this email in error, please notify the sender immediately and delete it from your computer. While we strive to keep our network free from computer viruses, we do not guarantee that this transmission is virus-free and will not be liable for any damages resulting from any transmitted viruses.</em></small>
  </div>

  <script>
    // Add animation or interactive elements using JavaScript or libraries like jQuery
    // Example: fade in effect
    document.addEventListener("DOMContentLoaded", function(event) {
      var container = document.querySelector(".container");
      container.style.opacity = 0;
      var fadeIn = function() {
        var op = 0.1;
        var timer = setInterval(function() {
          if (op >= 1) {
            clearInterval(timer);
          }
          container.style.opacity = op;
          container.style.filter = "alpha(opacity=" + op * 100 + ")";
          op += op * 0.1;
        }, 10);
      };
      fadeIn();
    });
  </script>
</body>
</html>
