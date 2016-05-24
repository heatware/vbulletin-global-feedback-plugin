Official HeatWare Plugin for vBulletin
======
Since 1999, [HeatWare](http://wwww.heatware.com) has provided free use of its user feedback system, enabling forum users to Buy/Sell/Trade with confidence. We have officially released a vBulletin plugin that allows forums to display user feedback statistics from HeatWare in the user's forum profile. HeatWare is a ***global*** feedback system, therefore there is no need to build reputation on EACH forum you visit.

## Compatibility 
* **Supported:** vBulletin 4.2.3
* **Not Supported:** vBulletin 5.x

## Download
* [Version 1.0.0](https://github.com/heatware/vbulletin-global-feedback-plugin/files/280080/heatware_vb4_1.0.0.zip)

## Installation
* Download the latest release and unzip the package
* Upload the contents of the folder ***upload_this*** to the vBulletin root folder
* Open ***heatware_config.php*** and enter the API key that you obtained after contacting HeatWare

## How to Link Forum Account with Heatware account
After the plugin is installed, an option called **HeatWare Feedback** will appear in the user's profile with ***Yes/No*** options. When set to ***Yes***, the plugin will use the forum user's E-mail address and search for a HeatWare account with that e-mail address. HeatWare allows multiple e-mail addresses to be associated with the user's account, so there is no need for users to change their forum e-mail address. Note: After enabling this option, it may take ~30 minutes for statistics to appear.

**Where are the feedback stats displayed?**
* A tab called ***HeatWare Feedback*** will be added to the user's profile
* When a user posts a message, the Positive/Negative/Neutral feedback count will be displayed under the user's avatar

![vBulletin HeatWare Plugin](http://i.imgur.com/Q6gkIB7.png "vBulletin HeatWare Plugin")
![vBulletin HeatWare Plugin](http://i.imgur.com/Woikj1L.png "vBulletin HeatWare Plugin")

## Implementation / Design
* vBulletin Scheduled Tasks will run every 10 minutes to look for any user that enabled the plugin profile option. In addition, it will also search for HeatWare users with the forum user's email address, and fetch the feedback statistics.
* A scheduled task will run once per day to send HeatWare plugin usage statistics.
* User's HeatWare statistics will update no more than once per day
* There is a limit on how many API calls will be made per invocation to reduce forum and HeatWare load. 

## Contributors
I am looking for the community to help with bug fixes, feature development, and testing compatibility with various vBulletin versions.

## Versions
### 1.0.0
* Initial release

## Contact
#### heat23
* Homepage: http://www.heatware.com/u/2 
* E-mail: heatware.support(at)gmail(dot)com 
