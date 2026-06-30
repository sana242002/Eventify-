<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=register.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "eventify", 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName       = $conn->real_escape_string($_POST['fullName']);
    $email          = $conn->real_escape_string($_POST['email']);
    $eventName      = $conn->real_escape_string($_POST['eventName']);
    $eventStartDate = date('Y-m-d', strtotime($_POST['eventStartDate']));
    $eventStartTime = $conn->real_escape_string($_POST['eventStartTime']);
    $eventEndDate   = date('Y-m-d', strtotime($_POST['eventEndDate']));
    $eventEndTime   = $conn->real_escape_string($_POST['eventEndTime']);
    $city           = $conn->real_escape_string($_POST['city']);

    $sql = "INSERT INTO registrations 
            (full_name, email, event_name, event_start_date, event_start_time, event_end_date, event_end_time, city)
            VALUES 
            ('$fullName', '$email', '$eventName', '$eventStartDate', '$eventStartTime', '$eventEndDate', '$eventEndTime', '$city')";

    if ($conn->query($sql) === TRUE) {
        header("Location: theme.html");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration — Eventify</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --orange: #FF8C00; --orange-deep: #ff4500; --orange-glow: rgba(255,140,0,0.35); }
        body { font-family: 'Inter', Arial, sans-serif; background: #080818; color: #fff; min-height: 100vh; overflow-x: hidden; }

        /* ── Themed background image ── */
        .bg-image {
            position: fixed; inset: 0; z-index: 0;
            background-image: url('https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=1600&q=80');
            background-size: cover; background-position: center;
            filter: brightness(0.45) saturate(0.85);
            pointer-events: none;
        }
        .bg-overlay {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                linear-gradient(180deg, rgba(8,8,24,0.45) 0%, rgba(8,8,24,0.75) 100%);
        }

        .navbar-custom {
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 56px;
            background: rgba(8,8,24,0.85);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            backdrop-filter: blur(24px);
        }
        .logo-row { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .logo-cube { width: 36px; height: 36px; position: relative; transform-style: preserve-3d; animation: spinCube 9s linear infinite; }
        @keyframes spinCube { 0% { transform: rotateX(20deg) rotateY(0deg); } 100% { transform: rotateX(20deg) rotateY(360deg); } }
        .cube-face { position: absolute; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 15px; color: #fff; border: 1px solid rgba(255,140,0,0.35); }
        .cf-front  { background: rgba(255,130,0,0.90); transform: translateZ(18px); }
        .cf-back   { background: rgba(200,80,0,0.70);  transform: rotateY(180deg) translateZ(18px); }
        .cf-left   { background: rgba(180,60,0,0.65);  transform: rotateY(-90deg) translateZ(18px); }
        .cf-right  { background: rgba(220,100,0,0.75); transform: rotateY(90deg)  translateZ(18px); }
        .cf-top    { background: rgba(255,160,0,0.85); transform: rotateX(90deg)  translateZ(18px); }
        .cf-bottom { background: rgba(160,50,0,0.55);  transform: rotateX(-90deg) translateZ(18px); }
        .logo-text { font-size: 1.25rem; font-weight: 800; color: #fff; letter-spacing: -0.3px; }
        .logo-text span { color: var(--orange); }

        .nav-links-custom { display: flex; align-items: center; gap: 4px; list-style: none; }
        .nav-links-custom a {
            color: rgba(255,255,255,0.55); font-size: 13.5px; font-weight: 500;
            text-decoration: none; padding: 8px 14px; border-radius: 9px;
            transition: all 0.2s;
        }
        .nav-links-custom a:hover { color: #fff; background: rgba(255,255,255,0.07); }
        .nav-cta {
            background: linear-gradient(135deg, var(--orange), var(--orange-deep)) !important;
            color: #fff !important; font-weight: 700 !important;
            box-shadow: 0 4px 18px var(--orange-glow); margin-left: 8px;
        }
        .nav-cta:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(255,140,0,0.55) !important; }

        .page-wrap { position: relative; z-index: 1; padding: 60px 24px 80px; }
        .form-card {
            max-width: 780px; margin: 0 auto;
            background: rgba(8,8,20,0.55);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 24px;
            padding: 48px 52px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 30px 80px rgba(0,0,0,0.5);
        }

        .form-header { margin-bottom: 36px; }
        .sec-tag {
            font-size: 10.5px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--orange); margin-bottom: 10px;
            display: flex; align-items: center; gap: 8px;
        }
        .sec-tag::before { content: ''; display: block; width: 18px; height: 1.5px; background: var(--orange); }
        .form-header h1 { font-size: 2rem; font-weight: 900; letter-spacing: -1px; margin-bottom: 8px; }
        .form-header p { font-size: 0.92rem; color: rgba(255,255,255,0.42); }

        .form-row { display: flex; gap: 18px; }
        .form-row .form-group { flex: 1; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.45); letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 9px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 17px; color: rgba(255,255,255,0.25); pointer-events: none; }
        .input-wrap input,
        .input-wrap select {
            width: 100%; padding: 13px 14px 13px 44px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px; color: #fff; font-size: 14px;
            font-family: 'Inter', sans-serif; outline: none; transition: all 0.2s;
            appearance: none; -webkit-appearance: none;
        }
        .input-wrap select { cursor: pointer; }
        .input-wrap select option { background: #12122a; color: #fff; }
        .input-wrap input::placeholder { color: rgba(255,255,255,0.20); }
        .input-wrap input:focus,
        .input-wrap select:focus {
            border-color: rgba(255,140,0,0.50);
            background: rgba(255,255,255,0.09);
            box-shadow: 0 0 0 3px rgba(255,140,0,0.10);
        }
        .input-wrap input.input-error { border-color: rgba(255,60,60,0.50); box-shadow: 0 0 0 3px rgba(255,60,60,0.10); }
        .field-error { font-size: 11.5px; color: rgba(255,100,100,0.85); margin-top: 5px; display: none; }
        .field-error.show { display: block; }

        .ui-datepicker { background: #12122a; border: 1px solid rgba(255,140,0,0.3); border-radius: 12px; color: #fff; font-family: 'Inter', sans-serif; }
        .ui-datepicker-header { background: rgba(255,140,0,0.15); border: none; border-radius: 12px 12px 0 0; color: #fff; }
        .ui-datepicker td span, .ui-datepicker td a { color: rgba(255,255,255,0.75); }
        .ui-datepicker td a:hover { background: rgba(255,140,0,0.25); color: #fff; border-radius: 6px; }
        .ui-datepicker .ui-state-active { background: var(--orange); color: #fff; border-radius: 6px; }
        .ui-datepicker-prev, .ui-datepicker-next { color: var(--orange) !important; }

        .section-sep { height: 1px; background: rgba(255,255,255,0.06); margin: 10px 0 24px; }

        .btn-submit {
            width: 100%; margin-top: 10px; padding: 15px;
            background: linear-gradient(135deg, var(--orange), var(--orange-deep));
            color: #fff; font-size: 15px; font-weight: 700;
            border: none; border-radius: 13px; cursor: pointer;
            box-shadow: 0 8px 28px var(--orange-glow); transition: all 0.25s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 14px 40px rgba(255,140,0,0.50); }

        @media (max-width: 640px) {
            .form-card { padding: 32px 20px; }
            .form-row { flex-direction: column; gap: 0; }
            .navbar-custom { padding: 14px 20px; }
        }
    </style>
</head>
<body>
<div class="bg-image"></div>
<div class="bg-overlay"></div>

<nav class="navbar-custom">
    <a class="logo-row" href="main.php">
        <div class="logo-cube">
            <div class="cube-face cf-front">E</div>
            <div class="cube-face cf-back"></div>
            <div class="cube-face cf-left"></div>
            <div class="cube-face cf-right"></div>
            <div class="cube-face cf-top"></div>
            <div class="cube-face cf-bottom"></div>
        </div>
        <span class="logo-text">Event<span>ify</span></span>
    </a>
    <ul class="nav-links-custom">
        <li><a href="main.php">Home</a></li>
        <li><a href="contact.html">Contact Us</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="register.php" class="nav-cta">Create Event</a></li>
    </ul>
</nav>

<div class="page-wrap">
    <div class="form-card">
        <div class="form-header">
            <div class="sec-tag">Step 1 of 3</div>
            <h1>Event Registration 🎟️</h1>
            <p>Fill in the details below to register your event.</p>
        </div>

        <form id="eventForm" method="POST" action="register.php" onsubmit="return validateForm()">

            <div class="form-row">
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <div class="input-wrap">
                        <i class="ti ti-user"></i>
                        <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" required>
                    </div>
                    <div class="field-error" id="fullNameError"></div>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrap">
                        <i class="ti ti-mail"></i>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required>
                    </div>
                    <div class="field-error" id="emailError"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="eventName">Event Name</label>
                <div class="input-wrap">
                    <i class="ti ti-calendar-event"></i>
                    <input type="text" id="eventName" name="eventName" placeholder="Enter event name" required>
                </div>
                <div class="field-error" id="eventNameError"></div>
            </div>

            <div class="section-sep"></div>

            <div class="form-row">
                <div class="form-group">
                    <label for="eventStartDate">Event Start Date</label>
                    <div class="input-wrap">
                        <i class="ti ti-calendar"></i>
                        <input type="text" class="datepicker" id="eventStartDate" name="eventStartDate" placeholder="mm/dd/yyyy" required>
                    </div>
                    <div class="field-error" id="eventStartDateError"></div>
                </div>
                <div class="form-group">
                    <label for="eventStartTime">Event Start Time</label>
                    <div class="input-wrap">
                        <i class="ti ti-clock"></i>
                        <input type="time" id="eventStartTime" name="eventStartTime" required>
                    </div>
                    <div class="field-error" id="eventStartTimeError"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="eventEndDate">Event End Date</label>
                    <div class="input-wrap">
                        <i class="ti ti-calendar"></i>
                        <input type="text" class="datepicker" id="eventEndDate" name="eventEndDate" placeholder="mm/dd/yyyy" required>
                    </div>
                    <div class="field-error" id="eventEndDateError"></div>
                </div>
                <div class="form-group">
                    <label for="eventEndTime">Event End Time</label>
                    <div class="input-wrap">
                        <i class="ti ti-clock"></i>
                        <input type="time" id="eventEndTime" name="eventEndTime" required>
                    </div>
                    <div class="field-error" id="eventEndTimeError"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="city">City</label>
                <div class="input-wrap">
                    <i class="ti ti-map-pin"></i>
                    <input type="text" id="city" name="city" placeholder="Enter your city" required>
                </div>
                <div class="field-error" id="cityError"></div>
            </div>

            <button type="submit" class="btn-submit">Next — Choose Theme →</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(function() {
        $(".datepicker").datepicker({
            dateFormat: "mm/dd/yy",
            showAnim: "slideDown"
        });
    });

    function validateForm() {
        let isValid = true;
        document.querySelectorAll('.field-error').forEach(el => { el.textContent = ''; el.classList.remove('show'); });
        document.querySelectorAll('input').forEach(el => el.classList.remove('input-error'));

        const fullName = document.getElementById('fullName').value;
        if (!/^[A-Za-z\s]+$/.test(fullName)) {
            showError('fullNameError', 'fullName', 'Full Name can only contain letters and spaces.');
            isValid = false;
        }

        const email = document.getElementById('email').value;
        if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(email)) {
            showError('emailError', 'email', 'Please enter a valid email address.');
            isValid = false;
        }

        const eventStartDate = document.getElementById('eventStartDate').value;
        const today = new Date().toISOString().split('T')[0];
        if (new Date(eventStartDate) < new Date(today)) {
            showError('eventStartDateError', 'eventStartDate', 'Event start date cannot be in the past.');
            isValid = false;
        }

        const eventEndDate = document.getElementById('eventEndDate').value;
        if (new Date(eventEndDate) < new Date(eventStartDate)) {
            showError('eventEndDateError', 'eventEndDate', 'Event end date cannot be before start date.');
            isValid = false;
        }

        const city = document.getElementById('city').value;
        if (city.trim() === '') {
            showError('cityError', 'city', 'City is required.');
            isValid = false;
        }

        return isValid;
    }

    function showError(errId, inputId, msg) {
        const el = document.getElementById(errId);
        el.textContent = msg; el.classList.add('show');
        document.getElementById(inputId).classList.add('input-error');
    }
</script>
</body>
</html>