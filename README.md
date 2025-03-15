# ElevateEd
- [Elevate-Ed - Unlock Your Potential With AI - Powered personalized Learning](#elevate-ed---unlock-your-potential-with-ai---powered-personalized-learning)
  - [Project Overview](#project-overview)
  - [Objective](#objective)
  - [Key Features](#key-features)
    - [Personalized Learning Experience](#personalized-learning-experience)
    - [Interactive Learning Tools](#interactive-learning-tools)
    - [Career Development](#career-development)
  - [How to Install Dependencies](#how-to-install-dependencies)
  - [For windows](#for-windows)
  - [How to Run the Application](#how-to-run-the-application)
  - [Contributors](#contributors)

# Elevate-Ed - Unlock Your Potential With AI - Powered personalized Learning

Here’s a structured version with **Project Overview** and **Objective** sections:

---

## Project Overview

**Elevate-Ed** is a dynamic web application designed to offer a highly personalized learning experience. It leverages AI to analyze user data, providing tailored recommendations for courses, certifications, and structured roadmaps that align with individual skills, goals, and preferences. To enhance user engagement, Elevate-Ed incorporates features like gamification, community collaboration, and real-time support, fostering an interactive and goal-oriented learning environment.

## Objective

The primary objective of **Elevate-Ed** is to empower learners by:

- Summarizing documents and web links to help users quickly grasp key information.
- Delivering customized learning pathways based on individual needs.
- Recommending relevant courses and certifications to achieve specific goals.
- Encouraging continuous learning through gamified elements and community-driven support.
- Offering a resume builder to help users professionally showcase their skills and accomplishments.
- Providing real-time assistance to ensure learners stay motivated and on track.

<details>
<summary>
<h2>Project Structure</h2>
</summary>

```
/
├── Dashboard/
│ ├── TextSummarizer-master/
│ │ ├── Templates/
│ │ │ ├── index.html
│ │ ├── static/
│ │ │ ├── css/
│ │ │ │ ├── styles.css
│ │ │ └── script/
│ │ │ └── script.js
│ │ ├── app.py
│ │ ├── package-lock.json
│ │ ├── requirements.txt
│ │ └── tempCodeRunnerFile.py
│ ├── uploads/
│ │ ├── 2_1738258894.png
│ │ ├── 2_1738258917.png
│ │ ├── 2_1738259934.png
│ │ ├── 2_1738260249.png
│ │ ├── 2_1738260352.png
│ │ ├── 4_1738441563.png
│ │ └── 4_1738441601.png
│ ├── UD_styles.css
│ ├── default_avatar.jpg
│ ├── delete_skill.php
│ ├── header.php
│ ├── header_script.js
│ ├── header_styles.css
│ ├── landing_page.php
│ ├── landing_styles.css
│ ├── logout.php
│ ├── profile.css
│ ├── profile.js
│ ├── progress.js
│ ├── progress.php
│ ├── progress_styles.css
│ ├── update_profile.php
│ ├── user_dashboard.php
│ └── view_profile.php
├── WebDevelopmentCourse/
│ ├── Group 1.png
│ ├── auth-styles.css
│ ├── styles.css
│ ├── auth.js
│ ├── db.php
│ ├── logout.php
│ ├── signin.php
│ ├── signup.php
│ ├── ElevateEd_index.html
│ ├── index.html
│ ├── signin.html
│ └── signup.html
├── Group 1.png
├── auth-styles.css
├── styles.css
├── auth.js
├── db.php
├── logout.php
├── signin.php
├── signup.php
├── ElevateEd_index.html
├── index.html
├── signin.html
├── signup.html
└── README.md
```

</details>

## Key Features

### Personalized Learning Experience

Experience education tailored to your unique learning style and pace, powered by advanced AI algorithms.

- **AI-Driven Learning Paths:** Smart recommendations based on your skills and interests.
- **Adaptive Learning Speed:** Learn at your own pace with dynamic content adjustments.
- **Custom Learning Goals:** Set and achieve personalized milestones.

### Interactive Learning Tools

Engage with cutting-edge tools designed to make your educational journey both effective and enjoyable.

- **Interactive Video Lessons:** Visual, engaging content for better understanding.
- **Hands-on Practice:** Apply concepts through real-world exercises.
- **Real-time Feedback:** Get instant insights to improve your performance.

### Career Development

Transform your learning into career success with industry-recognized certifications and professional networking opportunities.

- **Professional Certifications:** Boost your resume with globally recognized credentials.
- **Expert Mentorship:** Learn from industry leaders and experienced mentors.
- **Job Market Insights:** Stay updated with the latest trends and career opportunities.

## How to Install Dependencies

## For windows

- Download **Python** [from here](https://www.python.org/downloads/)
- Download **PHP** [from here](https://windows.php.net/download#php-8.4)
- Download **XAMPP** [from here](https://www.apachefriends.org/download.html)

1. **Clone the Repository**:

   ```powershell
   git clone https://github.com/venkatesh1545/ElevateEdNew.git
   cd ElevateEdNew
   ```

2. **Set PowerShell Execution Policy** (if needed):
   Ensure that you can run scripts in PowerShell. Open PowerShell as Administrator and run:

   ```powershell
   Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
   ```

3. **Create and Activate a Virtual Environment**:
   Create a virtual environment:

   ```powershell
   python -m venv venv
   ```

   Activate the virtual environment:

   ```powershell
   .\venv\Scripts\Activate.ps1
   ```

4. **Install Python Packages**:
   ```powershell
   pip install -r requirements.txt
   ```

## How to Run the Application

- Run Backend

```bash
cd Dashboard/TextSummarizer-master/
python app.py
```

- Open **XXAMP** and Start **Apache** and **MySQL**
- Then, click on this [link](localhost/ElevateEdNew)

## Hosted Link
You can access the hosted version of the application here: http://apps.technicalhub.io:5002/ElevateEd08/
```
Note: Try to open through HTTP port, not HTTPS.
```
**Video Submission Drive Link :** [Click here to view video](https://drive.google.com/file/d/1zeqptjN3Im-yT6mpwpaqSchqYDzAhp_G/view?usp=sharing)

**Prototype Submission Link :**
[Click here to view the Figma prototype](https://www.figma.com/design/7lnX2yVaXxM9ReoiI4mVrz/Untitled?node-id=0-1&t=onqGGS0TaqKKyuFT-1)
```
Note: To run the prototype in Figma, click on the "Run" button next to the right panel.
```

## Contributors

- [**venkatesh1545**](https://github.com/venkatesh1545)
- [**Karthik-Saladi5**](https://github.com/Karthik-Saladi5)
- [**Durgasriniharika**](https://github.com/Durgasriniharika)
