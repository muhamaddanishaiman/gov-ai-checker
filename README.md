# Gov AI Checker (Pemeriksa Dokumen Ai)

**Gov AI Checker** is a web-based, AI-powered document validation tool designed to assist Malaysian citizens in preparing supporting documents for government assistance (Bantuan) programs. By instantly analyzing Identity Cards (IC) and Salary Slips using advanced Generative AI, it helps applicants spot missing, blurry, or invalid information *before* official submission, thereby increasing approval rates and reducing administrative backlogs.

---

## Features

- **Instant AI Validation**: Upload an IC or Salary Slip to receive immediate feedback on its validity.
- **Actionable Suggestions**: The system identifies specific issues (e.g., blurry text, missing names) and provides helpful suggestions to correct them.
- **Dual Language Support**: Fully accessible interfaces and AI feedback in both Bahasa Melayu and English.
- **Privacy First**: Secure document processing. No user data or uploaded files are saved or retained on the server.
- **No Account Required**: Free and open for all citizens to use immediately.

---

## Tech Stack

- **Backend**: [Laravel](https://laravel.com/) (PHP 8.2)
- **Frontend**: Blade Templates, [Tailwind CSS](https://tailwindcss.com/), Vanilla JavaScript
- **AI Engine**: [Google Gemini API](https://ai.google.dev/) (`gemini-2.5-flash`)
- **Database**: SQLite (Zero-configuration storage)
- **Deployment**: Docker, designed for serverless environments like Google Cloud Run

---

## Local Setup Instructions

Follow these instructions to run the application locally for development and testing.

### Prerequisites
Ensure you have the following installed on your machine:
- **PHP** >= 8.2
- **Composer** (PHP dependency manager)
- **Node.js** & **npm**
- A **Google Gemini API Key** (Get one from [Google AI Studio](https://aistudio.google.com/))

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/gov-ai-checker.git
cd gov-ai-checker/gov-ai
```

### 2. Install Dependencies
Install both PHP backend dependencies and JavaScript frontend assets.
```bash
composer install
npm install
```

### 3. Environment Configuration
Copy the example environment file and set up your local configuration.
```bash
cp .env.example .env
```
Open the `.env` file and set your Gemini API Key:
```env
GEMINI_API_KEY="your_actual_gemini_api_key_here"
```
Generate your application key:
```bash
php artisan key:generate
```

### 4. Build Frontend Assets
Compile the Tailwind CSS and JavaScript using Vite.
```bash
npm run build
# Alternatively, use `npm run dev` to watch for changes during development
```

### 5. Run the Application
Start the local Laravel development server.
```bash
php artisan serve
```
The application should now be running at `http://localhost:8000`.

---

## Docker Setup Instructions (Production / Testing)

The project includes a multi-stage `Dockerfile` optimized for production, perfect for deployment on platforms like Google Cloud Run.

### Prerequisites
- **Docker** installed and running on your machine.

### 1. Build the Docker Image
Navigate to the root directory containing the `Dockerfile` (`gov-ai-checker/gov-ai`) and run:
```bash
docker build -t gov-ai-checker .
```

### 2. Run the Container
Run the container, making sure to pass in the required environment variables. Cloud Run typically defaults to port 8080, which is what the Dockerfile exposes.
```bash
docker run -d \
  -p 8080:8080 \
  -e GEMINI_API_KEY="your_actual_gemini_api_key_here" \
  -e APP_KEY="base64:your_generated_app_key_here" \
  --name gov-ai-checker-app \
  gov-ai-checker
```

*(Note: You can find your `APP_KEY` in the `.env` file after running `php artisan key:generate` during local setup).*

### 3. Access the Application
The app will be available at `http://localhost:8080`.

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
