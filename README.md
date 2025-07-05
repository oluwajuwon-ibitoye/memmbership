# Membership Registration App

This is a secure PHP-based membership registration system with:

- ✅ Email confirmation via PHPMailer
- ✅ SMS alerts using Twilio
- ✅ Google reCAPTCHA v2 protection
- ✅ MySQL database integration

## 🚀 Deployment Notes

To deploy:
1. Upload to a PHP-enabled host (e.g., cPanel, Render, Heroku)
2. Set up your MySQL database and credentials
3. Install dependencies with `composer install`
4. Set up `.env` for secure config (Twilio, SMTP, DB)

## 📂 Structure
- `index.html` – Registration form with reCAPTCHA
- `register.php` – Handles DB storage, email, and SMS
- `thankyou.html` – Shown after successful registration
- `style.css` – Clean responsive styling

## ⚙️ Required Packages
```
composer require twilio/sdk phpmailer/phpmailer
```

## 📌 Environment Variables (Suggested `.env`)
```
TWILIO_SID=your_sid
TWILIO_TOKEN=your_token
TWILIO_PHONE=+1234567890
SMTP_HOST=smtp.yourprovider.com
SMTP_USER=your_email
SMTP_PASS=your_password
SMTP_PORT=587
RECAPTCHA_SECRET=your_recaptcha_secret
```

## 🔐 Security
- Uses PDO prepared statements to prevent SQL injection
- reCAPTCHA v2 integration for bot protection
