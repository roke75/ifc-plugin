# Instant Feedback Collector

Instant Feedback Collector allows you to create and manage interactive surveys on your WordPress site, offering real-time results and a responsive user experience.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
  - [Adding a New Question](#adding-a-new-question)
  - [Displaying the Survey Form](#displaying-the-survey-form)
  - [Displaying Real-Time Results](#displaying-real-time-results)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)
- [Support](#support)
- [Acknowledgements](#acknowledgements)

## Features

- **Real-Time Results:** Answers update automatically without page reloads.
- **Manage Questions and Answers:** Add, edit, and delete questions and their associated answers through an intuitive admin panel.
- **Responsive Design:** Answers are displayed in a grid layout that adapts to different screen sizes.
- **Animations:** New answers appear with smooth animations to enhance user engagement.
- **Security:** Utilizes WordPress nonces and sanitization functions to protect your site.
- **Shortcodes:** Easily embed surveys and results on any page or post using shortcodes.

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
4. Note the **Question ID** from the list of questions; you will need this for displaying the survey.

### Displaying the Survey Form

To display the survey form on a page or post, use the following shortcode:

```wordpress
[survey id="X"]
```

Replace `X` with the **Question ID** of the survey you want to display.

### Displaying Real-Time Results

To display the real-time results of a survey, use the following shortcode:

```wordpress
[survey_results id="X"]
```

Replace `X` with the **Question ID** whose results you want to display.

## Screenshots

1. **Admin Panel - Adding a New Question**

   ![Adding a New Question](assets/screenshot-1.png)

2. **Survey Form on a Page**

   ![Survey Form](assets/screenshot-2.png)

3. **Real-Time Results**

   ![Real-Time Results](assets/screenshot-3.png)

4. **Admin Panel - Managing Questions**

   ![Managing Questions](assets/screenshot-4.png)

*Note: Screenshots are located in the `assets` directory.*

## Contributing

Contributions are welcome! Please follow these steps:

1. **Fork the repository.**
2. **Create a new branch:** `git checkout -b feature/your-feature-name`.
3. **Commit your changes:** `git commit -am 'Add some feature'`.
4. **Push to the branch:** `git push origin feature/your-feature-name`.
5. **Submit a pull request.**

For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the **GPLv2 or later** License - see the [LICENSE.txt](LICENSE.txt) file for details.

## Support

If you encounter any issues or have questions, please contact:

- **Email:** [roke00@gmail.com](mailto:roke00@gmail.com)
- **Website:** [https://github.com/roke75/ifc-plugin](https://github.com/roke75/ifc-plugin)
- **GitHub Issues:** [https://github.com/roke75/ifc-plugin/issues](https://github.com/roke75/ifc-plugin/issues)

## Acknowledgements

- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- Thanks to everyone who has supported the development of this plugin and provided feedback for its improvement.
