# Instant Feedback Collector

Instant Feedback Collector allows you to create and manage questions on your WordPress site, offering real-time results.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
  - [Adding a New Question](#adding-a-new-question)
  - [Displaying the Question Form](#displaying-the-question-form)
  - [Displaying Real-Time Results](#displaying-real-time-results)

## Features

- **Real-Time Results:** Answers update automatically without page reloads.
- **Manage Questions and Answers:** Add, edit, and delete questions and their associated answers through an admin panel.
- **Shortcodes:** Easily embed questions and results on any page or post using shortcodes.

## Installation

1. **Download the plugin's zip file** from the [GitHub repository](#).
2. **Upload the plugin** to your WordPress site:
   - Navigate to `Plugins` > `Add New` > `Upload Plugin`.
   - Select the downloaded zip file and click `Install Now`.
3. **Activate the plugin**:
   - After installation, click `Activate Plugin`.

## Usage

### Adding a New Question

1. In the WordPress admin dashboard, navigate to `Instant Feedback Collector`.
2. In the `Add New Question` section, enter your question text.
3. Click `Add Question`.
4. Note the **Question ID** from the list of questions; you will need this for displaying the question.

### Displaying the Question Form

To display the question form on a page or post, use the following shortcode:

```wordpress
[ifc id="X"]
```

Replace `X` with the **Question ID** of the question you want to display.

### Displaying Real-Time Results

To display the real-time results of a question, use the following shortcode:

```wordpress
[ifc_results id="X"]
```

### Displaying Real-Time Results in word cloud

To display the real-time results of a question in a word cloud, use the following shortcode:

```wordpress
[ifc_results id="X" view="word_cloud"]
```

Replace `X` with the **Question ID** whose results you want to display.
