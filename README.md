<p align="center"><img src="https://assets-global.website-files.com/5ce003ab7c5e2f444ffd130c/5df61598ce102070b786763a_1.png" width="220"></p>

# PXL.Widgets Json Challenge #

A file import script to Import json file into the database with uniform data. While name dates and boleans are stored the same way, i would like too find a method to split addresses into seperate data.\
The jobs are are batched and basic information is accesible through the web interface.

**Bonus (work in progress)**\
For File upload you can choose either json, excel or csv. I installed Laravel Excel and the same Import script for Accounts is being called from within the spreadsheet import. The batch information for spreadscheets is not yet integrated For future development xml and additional support could be added.

**Get started**\
after nstallation and setup you can choose to either visit the webroot of the application (default: http://127.0.0.1:8000) to upload a file or simply call command `php artisan import:accounts`. Files uploaded are stored in `storage\app\imports`, where the 3 default files are located.

## Requirement ##
- PHP7.1+
- MySQL 5.4 - 5.6
- Composer

## Installation and setup ## 
- Open folder in your terminal
- Run command in terminal to install packages from composer.json\
`composer install`
- Copy the .env.example file and rename it to .env
- setup url\
`APP_URL`
- Setup  database connection in your .env file\
`DB_DATABASE`\
`DB_USERNAME`\
`DB_PASSWORD`
- Run command in terminal to migrate database\
`php artisan migrate`
- Run command in terminal to start server (or setup your own webserver)\
`php artisan serve`

## Jobs ##
`App\Jobs\PrepareFile.php`\
Job handles the file and either sends data to the `ProcessImportData` job or to the `SpreadsheetAccountsImport` import. If the date is send to the `ProcessImportData` then the data is chunked into arrays (500 big).

`App\Jobs\ProcessImportData.php`\
Job simply calls `AccountsImport`


## Imports ##
`App\Imports\AccountsImport.php`\
This is where the data is being processed, checked and stored to the database.\
At first all keys from data array are being snake_cased by `ArrayKeyCaseConverter` helper. 
All ages from date_of_birth are checked for age and formatted the same way.
The full name is splitted with `NameSplitter` helper (the actual insert still has the full name stored) 

`App\Imports\SpreadsheetAccountsImport.php (work in progress)`\
This is where the spreadsheet is prepared and data is prepared for the actual import via `AccountsImport`.

## Helpers ##

`App\Helpers\ArrayKeyCaseConverter`\
Converts the keys of a multidimensional array.

`App\Helpers\NameSplitter`\
Full name is plitted in salutation, first name, last name and suffix.

## Commands ##

`App\Console\Coommands\ChooseAccountImport`\
Command line that lets you choose one of 3 default files in project.
`php artisan import:accounts`


## Logs ##
Two custom logs are created (`storage\logs`)

**validation**\
the validation error and the record is being stored.

**skipimport**\
The records with a date of birth not within 18 - 65 years old are being stored in this log. 
