@block @block_readabilityscore @javascript
Feature: Text Analysis Functionality
    In order to assess the readability of course content
    As a teacher
    I need to be able to analyze selected text

    Background:
        Given the following "users" exist:
            | username | firstname | lastname | email                |
            | teacher1 | Teacher   | 1        | teacher1@example.com |
        And the following "courses" exist:
            | fullname | shortname |
            | Course 1 | C1        |
        And the following "course enrolments" exist:
            | user     | course | role           |
            | teacher1 | C1     | editingteacher |

    Scenario: Analyze text and display score
        Given I log in as "teacher1"
        And I am on "Course 1" course homepage
        And I turn editing mode on
        And I add the "Readability Score" block
        When I click on "Scan" "button" in the "Readability Score" "block"
        And I simulate text selection with "This is a sample text for readability analysis."
        Then I should see "Readability Score:" in the "#readability-result" "css_element"
        And I should see "Difficulty Level:" in the "#readability-result" "css_element"
        And I should see a number followed by "Difficulty Level:" in the "#readability-result" "css_element"

    Scenario: Verify remediation suggestions
        Given I log in as "teacher1"
        And I am on "Course 1" course homepage
        And I turn editing mode on
        And I add the "Readability Score" block
        When I click on "Scan" "button" in the "Readability Score" "block"
        And I simulate text selection with "The proliferation of multifaceted technological advancements in contemporary society has engendered a paradigm shift in interpersonal communication methodologies. The ubiquity of sophisticated mobile devices, replete with myriad applications facilitating instantaneous information dissemination, has irrevocably altered the landscape of human interaction. This transformation, while ostensibly beneficial in its augmentation of connectivity, simultaneously engenders potential detrimental ramifications vis-à-vis the quality and depth of interpersonal relationships. The juxtaposition of enhanced communicative capabilities with the concomitant erosion of face-to-face dialogue presents a dichotomy that merits rigorous examination and analysis by sociological and psychological professionals. Furthermore, the inexorable integration of artificial intelligence into quotidian existence exacerbates the complexity of this phenomenon, necessitating a reevaluation of the fundamental nature of human cognition and emotional intelligence in an increasingly digitized world."
        Then I should see "Readability Score:" in the "#readability-result" "css_element"
        And I should see "Difficulty Level:" in the "#readability-result" "css_element"
        And I should see "Remediation Suggestions:" in the "#readability-result" "css_element"