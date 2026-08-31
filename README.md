# SmartToolz.in 🛠️

**SmartToolz** is a responsive SaaS web application providing a growing collection of fast, browser-friendly online tools for images, PDFs, text, developers, calculators, generators, utilities and more.

🌐 **Website:** https://smarttoolz.in/smart-toolz/

## ✨ Features

- 🚀 50+ online tools and a scalable tool registry
- 📱 Responsive desktop, tablet and mobile UI
- 🎨 Modern shared SmartToolz header and footer
- 🧭 One standard **All Tools** sidebar across tool pages
- 🔐 Google Login authentication
- 👤 User accounts and activity tracking
- ⚡ Credit-based SaaS usage system
- 🎁 Free plan with **1,000 credits/month**
- 💳 Paid Pro/Creator plans with monthly credits
- ➖ **1 credit = 1 tool generation/use**
- 🚫 Tools require login before use
- 📊 Credit and tool-usage transactions stored in the database
- 💰 Referral commissions and wallet/payout system
- 🔄 Monthly credit renewal
- 🛡️ Browser-side processing where supported, helping keep user files private

## 🧰 Tool Categories

### Image Tools

Image Compressor, Image Resizer, JPG/PNG/WebP converters, GIF Maker, Image Cropper, Image Rotator and more.

### PDF Tools

PDF to JPG/PNG, PNG to PDF, PDF Merger, PDF Splitter, PDF Compressor, Text to PDF and more.

### Text & Developer Tools

Word Counter, Case Converter, JSON Formatter, URL Encoder/Decoder, HTML Encoder/Decoder, Markdown to HTML, Base64 Encoder/Decoder, Text to Slug, Reverse Text, Sort Lines and more.

### Calculators & Utilities

Age Calculator, Percentage Calculator, BMI Calculator, Unit Converter, Stopwatch/Timer, Unix Timestamp, Timestamp Converter, UUID Generator, Random Number Generator and more.

### Generators

QR Code Generator, QR Reader, Password Generator, Lorem Ipsum Generator and other productivity utilities.

## 💳 SaaS Credit System

SmartToolz uses a simple usage-based credit model:

| Plan | Credits | Billing |
|---|---:|---|
| Free | 1,000/month | ₹0/month |
| Pro | Configured in database | Monthly |
| Creator | Configured in database | Monthly |

**Usage rule:** 1 successful tool generation/use consumes **1 credit**.

Credits are checked server-side before a metered operation. Successful usage is recorded in the credit transaction and tool-usage history. When credits reach zero, the user is prompted to upgrade.

## 🔐 Authentication & Activity

Tool operations are intended for authenticated users. Google Login creates/identifies the SmartToolz account, after which the application can associate tool usage and credit transactions with that user.

The database can record:

- User ID
- Tool slug
- Usage timestamp
- Credit amount and balance after usage
- Transaction description/type
- Hashed IP information for usage records

## 🎁 Referral & Earnings

Users can receive a referral code/link and earn eligible referral commissions from paid-plan purchases. Commission activity is stored in the SaaS referral/wallet tables and can be used for payout requests according to the configured rules.

## 🗂️ Project Structure

```text
smarttoolz.in/
├── smart-toolz/
│   ├── index.php              # Home page
│   ├── tool.php               # All Tools / tool registry
│   ├── header.php             # Shared header + tool intro
│   ├── footer.php             # Shared footer
│   ├── account.php            # User account / SaaS dashboard
│   ├── tools/                 # Individual online tools
│   └── saas/                  # Credits, plans, referrals, wallet and SaaS logic
├── creator-ai/                # Authentication / AI application area
└── README.md
```

## 🛠️ Development Notes

- PHP is used for the application layer.
- MySQL/MariaDB is used for persistent SaaS/account data.
- Shared components should be reused instead of duplicating headers, footers or sidebars in individual tools.
- Tool pages should remain responsive and usable on mobile devices.
- Database-backed operations should use prepared statements and authenticated user IDs.
- Credit deduction should happen server-side rather than relying only on JavaScript.

## 🚀 Deployment

1. Configure the database connection and required environment/configuration values.
2. Configure Google OAuth for the authentication flow.
3. Deploy the `smart-toolz` application under the required document root.
4. Ensure the PHP process can access the database and required files.
5. Verify login, credit deduction, activity logging, referral and payout flows.
6. Test every tool from both desktop and mobile layouts.

## 📌 Current Direction

SmartToolz is being developed as a full SaaS platform where **users get useful online tools and credits**, while the platform can monetize through paid plans, referrals and other configured revenue streams.

---

Made for fast, simple and useful web utilities. ❤️
