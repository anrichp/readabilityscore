# Readability Score Moodle Block

- [Readability Score Moodle Block](#readability-score-moodle-block)
  - [Overview](#overview)
  - [Research Objectives](#research-objectives)
  - [Key Findings](#key-findings)
  - [Features](#features)
  - [Requirements](#requirements)
  - [Installation](#installation)
  - [Usage](#usage)
    - [Adding the block to a course](#adding-the-block-to-a-course)
    - [Analysing text](#analysing-text)
    - [Viewing the dashboard](#viewing-the-dashboard)
  - [Interpreting the Gunning Fog Index](#interpreting-the-gunning-fog-index)
  - [Architecture](#architecture)
    - [Class Diagram](#class-diagram)
    - [Sequence Diagram](#sequence-diagram)
    - [Component Diagram](#component-diagram)
  - [Research Methodology](#research-methodology)
  - [Limitations and Future Work](#limitations-and-future-work)
  - [Contributing](#contributing)
  - [Licence](#licence)
  - [Support](#support)
  - [Credits](#credits)


## Overview

The Readability Score block is a Moodle plugin developed as part of a Master's dissertation in Computer Science. It allows users to analyse the readability of text content within Moodle courses using the Gunning Fog Index. The plugin aims to address limitations in existing Web Accessibility Evaluation Tools (WAETs), particularly in their coverage of WCAG 2.1 success criterion 3.1.5, which relates to reading level.

## Research Objectives

The primary objectives of this research project were:

1. Analyse the effectiveness of various readability scoring algorithms.
2. Design and develop a Moodle plugin that implements the most effective readability scoring algorithm.
3. Evaluate the plugin's effectiveness in identifying complex text and its ability to provide suggestions for improvement.

## Key Findings

- The Gunning Fog Index demonstrated the strongest positive correlation (0.64) with human-generated text readability levels among the evaluated algorithms.
- The plugin achieved an overall accuracy of 50.97% in classifying text difficulty levels when compared to the OneStopEnglish Corpus.
- Performance varied across difficulty levels: Intermediate (56.61%), Advanced (55.56%), and Elementary (40.74%).
- A strong correlation (0.90) was observed between the plugin's Gunning Fog Index scores and the corpus difficulty levels.
- The plugin demonstrated efficient performance with an average execution time of 0.0143 seconds and low memory utilisation.

## Features

- Calculate readability scores using the Gunning Fog Index
- Provide instant feedback on text complexity
- Offer targeted suggestions for improving readability
- Store and display historical readability data
- Dashboard for viewing readability statistics across pages

## Requirements

- Moodle 3.9 or higher
- PHP 7.3 or higher

## Installation

1. Open a terminal and navigate to your Moodle installation's `blocks` directory:
   ```
   cd /path/to/your/moodle/blocks/
   ```

2. Clone the repository:
   ```
   git clone https://github.com/anrichp/moodle-block_readabilityscore.git readabilityscore
   ```

3. Log in to your Moodle site as an admin and go to Site administration > Notifications.

4. Follow the on-screen instructions to complete the installation.

5. Once installed, you should see a success message.

## Usage

### Adding the block to a course

1. Turn editing on in your course.
2. Click "Add a block" and select "Readability Score" from the list.
3. The block will appear in your course sidebar.

### Analysing text

1. Click the "Scan" button in the Readability Score block.
2. Select the text you want to analyse on the page.
3. The block will display the Gunning Fog Index score and readability level.
4. Review the suggestions provided to improve the text's readability if needed.

### Viewing the dashboard

1. Click the "Dashboard" button in the Readability Score block.
2. Select a page URL from the dropdown to view its readability statistics.
3. Review the Gunning Fog Index scores and other details for the selected page.

## Interpreting the Gunning Fog Index

- 6: Readable by a Year 7 pupil (11-12 years old)
- 7: Fairly easy - Year 8 (12-13 years old)
- 8: Standard - Year 9 (13-14 years old)
- 9: Fairly difficult - Year 10 (14-15 years old)
- 10-11: Difficult - Year 11 to Year 12 (15-17 years old)
- 12: Very difficult - Year 13 (17-18 years old)
- 13-16: University level
- 17+: Postgraduate level

## Architecture

The Readability Score block is designed with a modular architecture to ensure maintainability and separation of concerns. Below are UML diagrams that illustrate the structure and interactions within the plugin.

### Class Diagram

This class diagram shows the main components of the Readability Score block and their relationships:

```mermaid
classDiagram
 class block_readabilityscore {
     +init()
     +get_content()
     +applicable_formats()
     +specialisation()
 }
 class TextStatistics {
     +word_count(text)
     +sentence_count(text)
     +complex_word_count(text)
     +gunning_fog(text)
     -is_complex_word(word)
     -syllable_count(word)
 }
 class block_readabilityscore_external {
     +process_text_parameters()
     +process_text(selectedtext, pageurl)
     +process_text_returns()
     -generate_remediation_suggestions(score, debug_info)
 }
 block_readabilityscore ..> TextStatistics : uses
 block_readabilityscore_external ..> TextStatistics : uses
```

### Sequence Diagram

This sequence diagram illustrates the process of analysing text and generating a readability score:

```mermaid
sequenceDiagram
    participant User
    participant Block as Readability Score Block
    participant API as External API
    participant Stats as TextStatistics
    participant DB as Database

    User->>Block: Clicks "Scan" button
    Block->>User: Prompts to select text
    User->>Block: Selects text
    Block->>API: Sends selected text
    API->>Stats: Calculates readability
    Stats-->>API: Returns statistics
    API->>API: Generates suggestions
    API->>DB: Stores score and text
    API-->>Block: Returns score and suggestions
    Block->>User: Displays results
```

### Component Diagram

This component diagram shows the high-level structure of the Readability Score block and its interactions with Moodle core:

```mermaid
graph TB
    subgraph "Moodle Core"
        DB[Database]
        Auth[Authentication]
    end
    subgraph "Readability Score Block"
        UI[Block UI]
        Calc[Score Calculator]
        API[External API]
        Suggest[Suggestion Generator]
    end
    UI --> Calc
    UI --> API
    Calc --> TextStats[TextStatistics Library]
    API --> Calc
    API --> Suggest
    API --> DB
    UI --> Auth
```

These diagrams provide a visual representation of the plugin's architecture:

1. The **Class Diagram** shows the main classes in the plugin and their relationships.
2. The **Sequence Diagram** illustrates the process flow when a user analyses text.
3. The **Component Diagram** provides a high-level view of the plugin's components and their interactions with Moodle core.

## Research Methodology
This project employed the Design Science Research Methodology (DSRM) framework, consisting of six phases:

1. Problem Identification and Motivation
2. Definition of Objectives for a Solution
3. Design and Development
4. Demonstration
5. Evaluation
6. Communication

The evaluation phase utilised the OneStopEnglish Corpus as a benchmark for assessing the plugin's accuracy in classifying text difficulty levels.

## Limitations and Future Work
- The evaluation relied solely on the OneStopEnglish Corpus, which may not represent the full diversity of educational texts.
- Classification thresholds for the Gunning Fog Index require further refinement.
- Future work could explore integrating multiple readability metrics and incorporating machine learning techniques for improved accuracy.

## Contributing

We welcome contributions to the Readability Score block. Please submit pull requests to our GitHub repository at https://github.com/anrichp/moodle-block_readabilityscore.

## Licence

This plugin is licensed under the GNU GPL v3 or later.

## Support

For support, please open an issue in our GitHub repository

## Credits

Developed by Anrich Potgieter.