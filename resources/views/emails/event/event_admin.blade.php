<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>New Join Request</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    <h2>{{ (!empty($details['sub_admin']))?$details['sub_admin']:'Event Join Request' }}</h2>
    <p>Dear,

    <p>
        <div class="card px-3 pt-3">
            <section class="main-body px-3 pb-3">
                <p>A new participant has requested to join the event:</p>

                <p><strong>Event Name:</strong> [{{ (!empty($details['event_data']->title))?$details['event_data']->title:'' }}]</p>
                <p><strong>Event Date:</strong> [{{ (!empty($details['event_data']->event_date))?date('Y-m-d', strtotime($details['event_data']->event_date)):'' }}]</p>
                <p><strong>Time:</strong> [{{ (!empty($details['event_data']->event_date))?date('H:i:s', strtotime($details['event_data']->event_date)):'' }}]</p>
                <p><strong>Location/Platform:</strong> [{{ (!empty($details['event_data']->location))?$details['event_data']->location:'' }}]</p>

                <h5>User Details:</h5>
                <p><strong>Name:</strong> [{{ (!empty($details['name']))?$details['name']:'' }}]</p>
                <p><strong>Email:</strong> [{{ (!empty($details['email']))?$details['email']:'' }}]</p>
                <p><strong>Phone:</strong> [{{ (!empty($details['phone']))?$details['phone']:'' }}]</p>

                <p>Please log in to the admin panel to review and confirm this request</p>

            </section>
        </div>
    </p>
    <p>Thank you.</p>

    <p><b>Best regards,</b><br>
      Recruitment Team<br>
      UK Management College<br>
      College House<br>
      Stanley Street<br>
      Openshaw<br>
      M11 1LE
    </p>
    <img src="https://ukmc.ac.uk/frontend/assets/img/logo/updated logo.png" alt="UKMC Logo" class="logo">
    <p>
        Email: <a href="mailto:info@ukmc.ac.uk">info@ukmc.ac.uk</a><br>
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
